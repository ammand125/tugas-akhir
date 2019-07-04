<div class="sufee-login"></div>
<div class="container">
    <div class="login-content">
        <div class="container">
            <div class="col">
                <div class="col-md-12 ">
                    <div class="mt-5">
                        <div class="card">
                            <!--header-->
                            <div class="card-header h5 text-center bg-danger text-light">LOGIN</div>
                            <?php echo $this->session->flashdata('message');  ?>
                        </div>
                        <!-- E-mail -->
                        <div class="card-body login-form">
                            <form method="post" action="<?php echo base_url('auth/login'); ?>">
                                <div class="form-group">
                                    <label>Email address</label>
                                    <input type="text" class="form-control" id="email" name="email" placeholder="Email" value="<?= set_value('email'); ?>">
                                    <?php echo form_error('email', '<div class="text-danger">*', '</div>'); ?>
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                    <?php echo form_error('password', '<div class="text-danger">*', '</div>'); ?>
                                </div>
                                <button type="submit" class="btn btn-outline-success btn-md btn-block">Sign in</button>
                                <div class="register-link m-t-15 text-center">
                                    <p>Don't have account ? <a href="<?= base_url('auth/registration'); ?>"> Sign Up Here</a></p>
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