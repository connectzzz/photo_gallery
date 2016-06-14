<?php

?>
<h2>Create User</h2>

            <form action="index.php?manage=on&action=create" method="post">
                <p>
                    <div class="error"><?php if(isset($errors['username'])){echo $errors['username'];} ?></div>
                    Username:
                    <input <?php if(isset($errors['username'])){echo 'class="error"';}?> type="text" name="username"
                            value="<?= isset($validation) ? $validation->postdata('username') : ''; ?>" />
                </p>
                <p>
                     <div class="error"><?php if(isset($errors['first_name'])){echo $errors['first_name'];} ?></div>
                    First Name:
                    <input <?php if(isset($errors['first_name'])){echo 'class="error"';}?> type="text" name="first_name"
                            value=" <?= isset($validation) ? $validation->postdata('first_name') : '';?>" />
                </p>
                <p>
                    <div class="error"><?php if(isset($errors['last_name'])){echo $errors['last_name'];} ?></div>
                    Last Name:
                    <input <?php if(isset($errors['last_name'])){echo 'class="error"';}?> type="text" name="last_name"
                            value="<?= isset($validation) ? $validation->postdata('last_name') : ''; ?>" />
                </p>
                <p>
                    <div class="error"><?php if(isset($errors['password'])){echo $errors['password'];} ?></div>
                    Password:
                    <input <?php if(isset($errors['password'])){echo 'class="error"';}?> type="password" name="password"
                            value="" />
                </p>

                <input type="submit" name="submit" value="Create User" />
            </form>
            <br />