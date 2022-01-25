<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <script async src="{{mix('js/app.js')}}"></script>
</head>

<body>
    bug report demo page<br><br>

    <button id="login">open devtools and hit button, console logs show the flow</button>

    <script>
        // request to login which returns a jwt directly
        function apiLogin() {
            axios.post('/api/login', {
                email: 'bug-report@laravel.com',
                password: 'bug-report'
            })
                .then((response) => {
                    if (response.status === 200) {
                        console.log('login successful, getting cookie')

                        getCookie(response.data.token);
                    }
                });
        }

        // token is used directly for this route on the first hit
        // a cookie 'laravel_token' is returned
        function getCookie(token) {
            axios.get('/user', {
                headers: {
                    'Authorization': 'Bearer ' + token
                }
            })
                .then((response) => {
                    if (response.status === 200) {
                        console.log('got cookie, trying to use it')

                        tryWithCookie();
                    }
                });
        }

        // same request as before , but no auth header
        // laravel_token cookie is sent, but gets a 401 
        function tryWithCookie() {
            axios.get('/user')
                .then((response) => {
                    console.log('passport cookie auth worked')
                })
                .catch((error) => {
                    if (error.response.status === 401) {
                        console.log('passport cookie auth 401')
                    }
                });
        }

        document.querySelector('#login').addEventListener('click', apiLogin);
    </script>
</body>

</html>
