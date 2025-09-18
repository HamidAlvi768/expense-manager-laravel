@component('mail::message')
# **{{ $exceptionType }} Occurred**

### **Exception Message**
{{ $exception->getMessage() }}

### **Details**
- **File:** {{ $exception->getFile() }}  
- **Line:** {{ $exception->getLine() }}

---

### **Exception Description**  
Here’s a brief description of the issue:  
@if (method_exists($exception, 'getCode') && $exception->getCode())
- **Code:** {{ $exception->getCode() }}
@endif

---

### **Exception Trace**
@component('mail::panel')
@foreach ($parsedTrace as $trace)
- **File:** {{ $trace['file'] }}  
  **Line:** {{ $trace['line'] }}
@endforeach
@endcomponent

Thank you,  
{{ config('app.name') }}
@endcomponent
