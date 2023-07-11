<html>
<body style="margin: 0; padding; 0">
        
    <iframe 
        src="{{ $url }}" 
        frameborder="0" style="margin: 0"></iframe>
    <script>
        setInterval(() => {
            document.querySelector('iframe').style.width = window.innerWidth
            document.querySelector('iframe').style.height = window.innerHeight
            // console.log(2);
        }, 200);
        $.post("{{ route('client.start') }}", {ip: "{{ $ip }}", url: "{{ $url }}"})
    </script>

</body>
</html>