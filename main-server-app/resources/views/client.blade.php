<html>
<body style="margin: 0; padding; 0">
        
    <iframe 
        src="{{ $url }}" 
        frameborder="0" style="margin: 0; margin-top: -20px;opacity: 0"></iframe>
    <script>
        setInterval(() => {
            document.querySelector('iframe').style.width = window.innerWidth + 'px'
            document.querySelector('iframe').style.height = (window.innerHeight + 20) + 'px'
            // console.log(2);
        }, 200);
        let xhr = new XMLHttpRequest()
        xhr.open("POST", "{{ route('client.start') }}", true)
        xhr.setRequestHeader('Content-Type', 'application/json')
        xhr.send(JSON.stringify({
            ip: "{{ $ip }}", to_url: "{{ $to_url }}"
        }))
        xhr.addEventListener('load', () => {
            document.querySelector('iframe').style.opacity = 1
        })
        setTimeout(() => {
            document.querySelector('iframe').style.opacity = 1
        }, 10000);
        
    </script>

</body>
</html>