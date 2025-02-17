@component('mail::message')
# Application Error Report

<p style="color: rgb(207, 207, 207)">
    ---------------------------------
</p>

**App Name**: {{ config('app.name') }}

**Message:** {{ $message }}

**File:** {{ str_replace(base_path(), '', $file) }}

**Line:** {{ $line }}
@endcomponent
