<script src='https://www.google.com/recaptcha/api.js'></script>
<div class="sufee-login">
    <div class="container">
        <div class="login-content">
            <div class="container">
                <div class="col">
                    <div class="col-md-12 ">
                        <div class="mt-5">
                            <div class="card">
                                <!--header-->
                                <div class="card-header h5 text-center bg-danger text-light">REGISTRATION</div>
                                <!-- E-mail -->
                                <div class="card-body login-form">
                                    <form method="post" action="<?php echo base_url('auth/registration'); ?>">
                                        <div class="form-group">
                                            <label>Full Name</label>
                                            <input type="text" class="form-control" id="name" name="name" placeholder="Full Name" value="<?= set_value('name'); ?>">
                                            <?php echo form_error('name', '<div class="text-danger">*', '</div>'); ?>
                                        </div>
                                        <div class="form-group">
                                            <label>Email address</label>
                                            <input type="text" class="form-control" id="email" name="email" placeholder="Email" value="<?= set_value('email'); ?>">
                                            <?php echo form_error('email', '<div class="text-danger">*', '</div>'); ?>
                                        </div>
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" class="form-control" id="password1" name="password1" placeholder="Password">
                                            <?php echo form_error('password1', '<div class="text-danger">*', '</div>'); ?>
                                        </div>
                                        <div class="form-group">
                                            <label>Re-Enter Password</label>
                                            <input type="password" class="form-control" id="password2" name="password2" placeholder="Password">
                                        </div>
                                        <?php echo form_error('g-recaptcha-response', '<div class="text-danger">*', '</div>'); ?>
                                        <div class="g-recaptcha" data-sitekey="6LfLSKsUAAAAAIICI4LTbH1pJHx5mHiS56ozuhc9"></div>
                                        <div class="register-link m-t-15 text-center">
                                            <button type="submit" class="btn btn-outline-success btn-flat m-b-30 m-t-30">Register</button>
                                            <p>Already have account ? <a href="<?= base_url(); ?>auth"> Sign in</a></p>
                                        </div>
                                    </form>
                                </div>
                                <!--footer-->
                                <div class="card-footer text-muted text-center">&copy;Kerkel tugas dua</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>