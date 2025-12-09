/**
 * Go Quick Async Job Handler
 * Helper để xử lý async jobs với polling
 */
class GoQuickAsyncHandler {
    constructor(options = {}) {
        this.baseUrl = options.baseUrl || '/go-quick';
        this.pollInterval = options.pollInterval || 2000; // 2 giây
        this.maxPollAttempts = options.maxPollAttempts || 300; // Tối đa 10 phút (300 * 2s)
        this.onProgress = options.onProgress || null;
        this.onComplete = options.onComplete || null;
        this.onError = options.onError || null;
        this.pollTimer = null;
        this.pollAttempts = 0;
    }

    /**
     * Start async job
     * @param {string} endpoint - API endpoint (process-cccd-async, process-pdf-async, etc.)
     * @param {FormData|Object} data - FormData hoặc object data
     * @returns {Promise<string>} job_id
     */
    async startJob(endpoint, data) {
        try {
            const url = `${this.baseUrl}/${endpoint}`;
            let options = {
                method: 'POST',
                headers: {},
                credentials: 'same-origin'
            };

            // Nếu là FormData, không set Content-Type (browser sẽ tự set)
            if (data instanceof FormData) {
                options.body = data;
                // Thêm CSRF token nếu có trong FormData
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (csrfToken && !data.has('_token')) {
                    data.append('_token', csrfToken);
                }
                if (csrfToken) {
                    options.headers['X-CSRF-TOKEN'] = csrfToken;
                }
            } else {
                options.headers['Content-Type'] = 'application/json';
                // Thêm CSRF token nếu có
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (csrfToken) {
                    options.headers['X-CSRF-TOKEN'] = csrfToken;
                }
                options.body = JSON.stringify(data);
            }

            const response = await fetch(url, options);
            const result = await response.json();

            if (!response.ok || result.status !== 'success') {
                throw new Error(result.message || 'Lỗi tạo job');
            }

            return result.data.job_id;
        } catch (error) {
            if (this.onError) {
                this.onError(error);
            }
            throw error;
        }
    }

    /**
     * Poll job status
     * @param {string} jobId 
     * @returns {Promise<Object>} job status
     */
    async getJobStatus(jobId) {
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const headers = {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            };
            if (csrfToken) {
                headers['X-CSRF-TOKEN'] = csrfToken;
            }
            
            const response = await fetch(`${this.baseUrl}/job-status/${jobId}`, {
                method: 'GET',
                headers: headers,
                credentials: 'same-origin'
            });
            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'Lỗi lấy status');
            }

            return result.data;
        } catch (error) {
            if (this.onError) {
                this.onError(error);
            }
            throw error;
        }
    }

    /**
     * Get job result
     * @param {string} jobId 
     * @returns {Promise<Object>} job result
     */
    async getJobResult(jobId) {
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const headers = {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            };
            if (csrfToken) {
                headers['X-CSRF-TOKEN'] = csrfToken;
            }
            
            const response = await fetch(`${this.baseUrl}/job-result/${jobId}`, {
                method: 'GET',
                headers: headers,
                credentials: 'same-origin'
            });
            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'Lỗi lấy kết quả');
            }

            return result.data;
        } catch (error) {
            if (this.onError) {
                this.onError(error);
            }
            throw error;
        }
    }

    /**
     * Start job và tự động poll cho đến khi hoàn thành
     * @param {string} endpoint 
     * @param {FormData|Object} data 
     * @returns {Promise<Object>} final result
     */
    async processWithPolling(endpoint, data) {
        // Start job
        const jobId = await this.startJob(endpoint, data);
        this.pollAttempts = 0;

        return new Promise((resolve, reject) => {
            this.pollTimer = setInterval(async () => {
                try {
                    this.pollAttempts++;

                    // Check max attempts
                    if (this.pollAttempts > this.maxPollAttempts) {
                        this.stopPolling();
                        reject(new Error('Timeout: Job mất quá nhiều thời gian'));
                        return;
                    }

                    // Get status
                    const status = await this.getJobStatus(jobId);

                    // Call progress callback với đầy đủ thông tin
                    if (this.onProgress) {
                        this.onProgress({
                            jobId,
                            status: status.status,
                            progress: status.progress,
                            message: status.message,
                            total_cccd: status.total_cccd,
                            processed_cccd: status.processed_cccd,
                            total_images: status.total_images,
                            processed_images: status.processed_images
                        });
                    }

                    // Check if completed
                    if (status.status === 'completed') {
                        this.stopPolling();
                        
                        // Get result
                        const result = await this.getJobResult(jobId);
                        
                        if (this.onComplete) {
                            this.onComplete(result, jobId);
                        }
                        
                        resolve(result);
                    } else if (status.status === 'failed') {
                        this.stopPolling();
                        const error = new Error(status.error || 'Job thất bại');
                        
                        if (this.onError) {
                            this.onError(error, jobId);
                        }
                        
                        reject(error);
                    }
                    // Nếu đang chạy hoặc pending, tiếp tục poll

                } catch (error) {
                    this.stopPolling();
                    
                    if (this.onError) {
                        this.onError(error, jobId);
                    }
                    
                    reject(error);
                }
            }, this.pollInterval);
        });
    }

    /**
     * Stop polling
     */
    stopPolling() {
        if (this.pollTimer) {
            clearInterval(this.pollTimer);
            this.pollTimer = null;
        }
        this.pollAttempts = 0;
    }

    /**
     * Get queue info (nếu API hỗ trợ)
     */
    async getQueueInfo() {
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const headers = {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            };
            if (csrfToken) {
                headers['X-CSRF-TOKEN'] = csrfToken;
            }
            
            const response = await fetch(`${this.baseUrl}/queue-info`, {
                method: 'GET',
                headers: headers,
                credentials: 'same-origin'
            });
            const result = await response.json();
            return result.data || null;
        } catch (error) {
            // API có thể chưa hỗ trợ, return null
            return null;
        }
    }
}

// Export for use
if (typeof window !== 'undefined') {
    window.GoQuickAsyncHandler = GoQuickAsyncHandler;
}

if (typeof module !== 'undefined' && module.exports) {
    module.exports = GoQuickAsyncHandler;
}

