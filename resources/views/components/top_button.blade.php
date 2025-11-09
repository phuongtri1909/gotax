<button id="topButton" class="btn btn-pry rounded-circle position-fixed bottom-0 end-0 m-4" 
        style="display: none; z-index: 1000;">
    <img src="{{ asset('images/svg/arrow-up.svg') }}" alt="Arrow Up" width="20" height="20">
</button>

<style>
    #topButton {
        width: 45px;
        height: 45px;
        transition: all 0.3s ease;
    }
    
    #topButton:hover {
        opacity: 1;
        transform: translateY(-5px);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const topButton = document.getElementById('topButton');
        
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                topButton.style.display = 'block';
            } else {
                topButton.style.display = 'none';
            }
        });
        
        topButton.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });
</script>