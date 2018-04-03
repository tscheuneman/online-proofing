@include('emails.components.header')
<table border="0" cellpadding="0" cellspacing="0" class="body">
    <tr>
        <td>&nbsp;</td>
        <td class="container">
            <div class="content">

                <!-- START CENTERED WHITE CONTAINER -->
                <span class="preheader">Thank you for registering</span>
                <table class="main">

                    <!-- START MAIN CONTENT AREA -->
                    <tr>
                        <td class="wrapper">
                            <table border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td>
                                        <h1>Hi {{$admin->first_name}}</h1>
                                        <p>You have been registered as a new Premedia team member at the Print and Imaging Lab</p>
                                        <p>Your email is: <strong>{{$admin->email}}</strong></p>
                                        <p>Here is your inital password: <strong>{{$pw}}</strong></p>
                                        <table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
                                            <tbody>
                                            <tr>
                                                <td align="left">
                                                    <table border="0" cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                        <tr>
                                                            <td> <a href="{{$redirect}}" target="_blank">Login</a> </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <p>Once you login in with your temporary password. You will be prompted to change it to a password of your choice</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- END MAIN CONTENT AREA -->
                </table>
@include('emails.components.footer')