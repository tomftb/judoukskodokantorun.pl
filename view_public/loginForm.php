<form action="" method="POST">
    <fieldset class="FIELD" ><legend style="text-align:left;"><span class="F_TYTUL">Logownie do systemu - CMS</span></legend>
    <img src="images/judo_logo_40.bmp" align="right" alt="logo" style="margin:0px;"/><br/>
    <p class="P_INF_LOG">Login : <input class="INP_LOG" type="text" name="login" value="<?php echo filter_input(INPUT_POST,'login'); ?>"></p>
    <p class="P_INF_LOG">Hasło : <input class="INP_LOG" type="password" name="haslo" /></p>
    <div width="240px">
        <span class="F_ERROR">
            <?=$errLoginForm;?>
        </span>
    <div>
    <input type="submit" value="Zaloguj" name="wyslij" class="button" />
    </fieldset>
</form>
