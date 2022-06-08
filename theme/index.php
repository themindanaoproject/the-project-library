<?php define('PAGE_TITLE','The Mindanao Project'); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <?php include SERVER_ROOT.'/theme/head/index.php'; ?>
    </head>
    <body>
        <?php include SERVER_ROOT.'/theme/sections/loader.php'; ?>
        <app id="main" xscope="main" class="hf wf">
            <section xpatch="@PageView" class="hf wf">
                <div xif="PageSvc.state=='index'">
                    <?php include SERVER_ROOT.'/theme/views/home.php'; ?>
                </div>
                <div xif="PageSvc.state=='error'" class="hf wf">
                    <?php include SERVER_ROOT.'/theme/views/errors.php'; ?>
                </div>
            </section>
        </app>
    </body>
</html>
