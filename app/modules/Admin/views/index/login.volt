<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login</title>

    {{ stylesheet_link('components/semantic-ui/dist/semantic.min.css') }}

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    {{ javascript_include('components/html5shiv/dist/html5shiv.min.js') }}
    {{ javascript_include('components/respond/dest/respond.min.js') }}
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
        <form class="ui form segment" method="post" action="{{ url.get() }}admin/index/login">
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
    </div>
</body>
</html>
