@extends('welcome')

@section('content')

<style>
    /* Optional responsive adjustments */
    @media (max-width: 360px) {
        .privacy-container {
            padding: 1rem;
        }
    }
</style>

{{-- Logo --}}
<div class="text-center mb-8">
    <img src="{{ asset('images/aaron-auth.png') }}"
         class="mx-auto w-auto"
         style="height: clamp(80px, 20vw, 144px);">
</div>

<div class="privacy-container mx-auto px-4 text-gray-700 space-y-6">
    <h1 class="text-3xl font-bold text-center text-gray-900">Privacy Policy</h1>

    <p>
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
        Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
    </p>

    <h2 class="text-2xl font-semibold mt-6">Information We Collect</h2>
    <p>
        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
        Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
    </p>

    <h2 class="text-2xl font-semibold mt-6">How We Use Your Information</h2>
    <p>
        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
        Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
    </p>

    <h2 class="text-2xl font-semibold mt-6">Sharing and Disclosure</h2>
    <p>
        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
        Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
    </p>

    <h2 class="text-2xl font-semibold mt-6">Security</h2>
    <p>
        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
        Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
    </p>

    <h2 class="text-2xl font-semibold mt-6">Your Rights</h2>
    <p>
        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
        Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
    </p>

    <h2 class="text-2xl font-semibold mt-6">Contact Us</h2>
    <p>
        If you have any questions about this Privacy Policy, please contact us at:
        <br>
        <strong>Email:</strong> support@example.com
        <br>
        <strong>Phone:</strong> +1 (555) 123-4567
    </p>
</div>

@endsection
