<?php if ($error): ?>
    <div><?php echo $error->getMessage() ?></div>
<?php endif ?>
<div>
    <?php if (!$canLogin): ?>
        <div style="color: red">
            You can not log on now. <br>
            Looks like you don't remember your username or password.
        </div>
    <?php else: ?>
        <form action="<?php echo $view['router']->path('login') ?>" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="_username" value="<?= $lastUsername ?>"/>

            <label for="password">Password:</label>
            <input type="password" id="password" name="_password"/>

            <!--
                If you want to control the URL the user
                is redirected to on success (more details below)
                <input type="hidden" name="_target_path" value="/account" />
            -->

            <button type="submit">login</button>
        </form>
    <?php endif; ?>
</div>
