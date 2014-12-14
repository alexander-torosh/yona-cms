<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Login</title>

        <link href="/vendor/semantic-1.0.0/semantic.min.css" rel="stylesheet" type="text/css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
            <script src="/vendor/js/html5shiv.js"></script>
            <script src="/vendor/js/respond.min.js"></script>
        <![endif]-->
        <style>
        .container {
            width: 400px;
            margin: 100px auto 0;
        }
        </style>
    </head>
    <body>
        <div class="container">
            <form class="ui form segment" method="post">
                <h1>Admin panel</h1>
                {{ flash.output() }}
                <div class="required field">
                    <label>Login</label>
                    <div class="ui icon input">
                        {{ form.render('login') }}
                        <i class="user icon"></i>
                    </div>
                </div>
                <div class="required field">
                    <label>Password</label>
                    <div class="ui icon input">
                        {{ form.render('password') }}
                        <i class="lock icon"></i>
                    </div>
                </div>
                <div class="ui error message">
                    <div class="header">Errors</div>
                </div>
                <input type="hidden" name="{{ security.getTokenKey() }}"
                       value="{{ security.getToken() }}"/>
                <input type="submit" id="submit" class="ui blue submit button" value="Log in">
            </form>
        </div> <!-- /container -->
    </body>
</html>
