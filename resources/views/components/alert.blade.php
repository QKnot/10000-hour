@props(['type' => 'info', 'dismissible' => true, 'autoClose' => true, 'duration' => 5000])

@php
    $alertClasses = [
        'success' => 'alert-success',
        'error' => 'alert-danger',
        'danger' => 'alert-danger',
        'warning' => 'alert-warning',
        'info' => 'alert-info',
    ];
    
    $icons = [
        'success' => 'bi-check-circle-fill',
        'error' => 'bi-x-circle-fill',
        'danger' => 'bi-exclamation-triangle-fill',
        'warning' => 'bi-exclamation-triangle-fill',
        'info' => 'bi-info-circle-fill',
    ];
    
    $alertClass = $alertClasses[$type] ?? $alertClasses['info'];
    $icon = $icons[$type] ?? $icons['info'];
@endphp

<div class="custom-alert {{ $alertClass }}" 
     data-auto-close="{{ $autoClose ? 'true' : 'false' }}" 
     data-duration="{{ $duration }}"
     role="alert">
    <div class="alert-content">
        <i class="bi {{ $icon }} alert-icon"></i>
        <div class="alert-message">
            {{ $slot }}
        </div>
        @if($dismissible)
        <button type="button" class="alert-close" aria-label="Close">
            <i class="bi bi-x"></i>
        </button>
        @endif
    </div>
</div>
