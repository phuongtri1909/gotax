@extends('client.layouts.app')

@section('title', $document->title . ' - Tài Liệu - ' . config('app.name'))
@section('description', strip_tags(substr($document->content, 0, 160)))

@section('content')
<section class="documentation-page container-page">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-4">
                <div class="documentation-sidebar">
                    <h5 class="sidebar-title">Mục Lục</h5>
                    <nav class="table-of-contents" id="tableOfContents">
                        <ul class="toc-list" id="tocList">
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="col-lg-9 col-md-8">
                <div class="documentation-content">
                    <h1 class="documentation-content-title">{{ $document->title }}</h1>
                    <div class="documentation-body" id="documentationBody">
                        {!! html_entity_decode($document->content ?? '', ENT_QUOTES, 'UTF-8') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
    @vite('resources/assets/frontend/css/pages/documentation.css')
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const content = document.getElementById('documentationBody');
            const tocList = document.getElementById('tocList');
            
            if (!content || !tocList) return;

            const headings = content.querySelectorAll('h1, h2, h3, h4, h5, h6');
            const tocItems = [];

            headings.forEach((heading, index) => {
                const id = 'heading-' + index;
                heading.id = id;
                
                const level = parseInt(heading.tagName.substring(1));
                const text = heading.textContent.trim();
                
                tocItems.push({
                    id: id,
                    level: level,
                    text: text
                });
            });

            tocItems.forEach(item => {
                const li = document.createElement('li');
                li.className = `toc-item toc-level-${item.level}`;
                
                const a = document.createElement('a');
                a.href = '#' + item.id;
                a.textContent = item.text;
                a.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.getElementById(item.id);
                    if (target) {
                        const headerOffset = 100;
                        const elementPosition = target.getBoundingClientRect().top;
                        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                        
                        window.scrollTo({
                            top: offsetPosition,
                            behavior: 'smooth'
                        });
                    }
                });
                
                li.appendChild(a);
                tocList.appendChild(li);
            });

            if (tocItems.length === 0) {
                tocList.innerHTML = '<li class="text-muted">Không có mục lục</li>';
            }
        });
    </script>
@endpush

