<?php if (!defined('IN_CODE')) die ('You cannot run this file directly.'); ?>

{xmb:html header}

    <form action="register/doregister/" method="post">
        <div class="head">
            Register
        </div>

        <table class="xtable">
            <tr class="xrow">
                <td class="xhead"><span class="c1">Required*</span></td>

                <td class="xhead"></td>
            </tr>

            <tr class="xrow">
                <td class="alt1 c2">Username</td>

                <td class="alt1"><input class="input" type="text" name="xmbusername" id="username" value="" onchange="check_username(this.value);"> <span id="usernamemessage"></span></td>
            </tr>

            <tr class="xrow">
                <td class="alt1 c2">Email</td>

                <td class="alt1"><input class="input" type="text" name="xmbemail" onchange="checkemail(this.value)" value=""> <span id="email"></span></td>
            </tr>

            <tr class="xrow">
                <td class="alt1 c2">Password</td>

                <td class="alt1"><input class="input" type="password" name="xmbpassword" id="password1input" onchange="checkpassword()" value=""> <span id="password"></span></td>
            </tr>

            <tr class="xrow">
                <td class="alt1 c2">Retype Password</td>

                <td class="alt1"><input class="input" type="password" name="xmbrepassword" id="password2input" onchange="checkpassword()" value=""> <span id="password2"></span></td>
            </tr><?php if ($options['registration_recaptcha'] == 1) { ?>

            <tr class="xrow">
                <td class="xhead"><span class="c1">Spam Control</span></td>

                <td class="xhead"></td>
            </tr>

            <tr class="xrow">
                <td class="alt1 c2">ReCaptcha</td>

                <td class="alt1"><?php echo $recaptcha;?>
                </td>
            </tr><?php  } ?>
            </table>

        <div class="foot c3">
            <input type="submit" class="submit" value="Register!">
        </div>
    </form>
    
<script type="text/javascript" src="application/javascript/xmb/register.js"></script>
    
{xmb:html footer}
