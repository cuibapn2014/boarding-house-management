@extends('master')
@section('title', 'Ối! Không tìm thấy mũi kim nào dưới đáy biển')
@push('css')
<link rel="preload" href="{{ asset('assets/images/hero-background.webp') }}" as="image" />
<style>
    .error-container {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }

  .error-content {
    text-align: center;
  }

  .error-content h1 {
    font-size: 6rem;
    font-weight: bold;
    margin-bottom: 1rem;
  }

  .error-content p {
    font-size: 1.5rem;
    margin-bottom: 2rem;
  }

  .lottie-animation {
    max-width: 400px;
    margin-bottom: 2rem;
  }
</style>
@endpush
@section('content')
<div class="container mt-5 d-flex items-center" style="min-height:70vh;">
    <div class="error-container mx-auto">
        <div class="lottie-animation"></div>
        <div class="error-content text-center">
            <h1>404</h1>
            <p>Ối! Không tìm thấy mũi kim nào dưới đáy biển</p>
            <a href="{{ route('home.index') }}" class="btn btn-primary">Trang Chủ</a>
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.9.6/lottie.min.js"></script>
<script>
    const animation = lottie.loadAnimation({
      container: document.querySelector('.lottie-animation'),
      renderer: 'svg',
      loop: true,
      autoplay: true,
      path: 'https://lottie.host/d987597c-7676-4424-8817-7fca6dc1a33e/BVrFXsaeui.json'
    });
</script>
@endpush