@if ($errors->any())
    <div class="alert alert-warning" role="alert">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning" role="alert">
        @if (is_array(session('warning')))
            @foreach (session('warning') as $warning)
                <p>{{ $warning }}</p>
            @endforeach
        @else
            <p>{{ session('warning') }}</p>
        @endif
    </div>
@endif

@if (session('success'))
    <script>
        swal({
            text : "{{ showMessages(session('success')) }}",
            icon : "success",
            button : "Fechar",
        });
    </script>
@endif

@if (session('error'))
    <script>
        swal({ 
            text : "{{ showMessages(session('error')) }}",
            icon : "error",
            button : "Fechar",
        });
    </script>
@endif

@php
function showMessages($messages)
{
    if (is_array($messages)) {
 
        $alerts = '';
        
        foreach ($messages as $message) {
            $alerts .= $message . '\\n';
        }

        $messages = $alerts;

    }

    return $messages;
}
@endphp