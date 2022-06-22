<?php
namespace User;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use Translation\Translation;

class Users {

    private $pdo;

    /**
     * @param \PDo $pdo
     */
    public function __construct(\PDo $pdo){
        $this->pdo = $pdo;
    }

    /**
     * Return customers users list
     * @return array
     */
    public function getCustomersUsers(): array{
        return $this->pdo->query("SELECT * FROM `user` WHERE `id_role` = 1 ORDER BY `company_name` ASC")->fetchAll();
    }

    /**
     * Return admin users list
     * @return array
     */
    public function getAdminUsers(): array{
        return $this->pdo->query("SELECT * FROM `user` WHERE `id_role` != 1 ORDER BY `name` ASC")->fetchAll();
    }

    /**
     * Return user by id
     * @param int $id
     * @return array
     */
    public function find (int $id): \User\User {
        $statement = $this->pdo->query("SELECT * FROM `user` WHERE `id` = $id LIMIT 1");
        $statement->setFetchMode(\PDO::FETCH_CLASS, \User\User::class);
        $result = $statement->fetch();
        if ($result === false) {
            throw new \Exception('Aucun résultat n\'a été trouvé');
        }
        return $result;
    }

    /**
     * Return user by email
     * @param string $email
     * @return array
     */
    public function findByEmail (string $email, string $function): \User\User {
        $statement = $this->pdo->query("SELECT * FROM `user` WHERE `email` = '$email' AND `confirmed_at` IS NOT NULL LIMIT 1");
        $statement->setFetchMode(\PDO::FETCH_CLASS, \User\User::class);
        $user = $statement->fetch();
        if ($user === false) {
            if($function == "connexion"){
                throw new \Exception(Translation::of('errorConnexion'));
            }elseif($function == "password"){
                throw new \Exception(Translation::of('errorForgottenPassword'));
            }elseif ($function == "event"){
                throw new \Exception(Translation::of('errorFindByEmail'));
            }

        }
        return $user;
    }

    /**
     * Return all users email
     * @return array
     */
    public function getUsersEmail (): array{
        return $this->pdo->query("SELECT `email` FROM `user`")->fetchAll();
    }

    /**
     * Modify datas before insertion in database (creation or update)
     * @param User $user
     * @param array $datas
     * @return User
     */
    public function hydrate(User $user, array $datas){
        $user->setCompanyName($datas['company_name']);
        $user->setName($datas['name']);
        $user->setFirstname($datas['firstname']);
        $user->setPhone($datas['phone']);
        if($datas['email']){
            $user->setEmail($datas['email']);
        }
        if($datas['password']){
            $user->setPassword(password_hash($datas['password'], PASSWORD_BCRYPT));
        }
        $user->setIdLang($datas['id_lang']);
        $user->setIdRole($datas['id_role']);
        $user->setConfirmationToken(bin2hex(random_bytes(32)));
        return $user;
    }

    /**
     * Insert new user in database
     * @param \User $user
     * @return bool
     */
    public function create(\User\User $user): void{
        $statement = $this->pdo->prepare('INSERT INTO `user` (`company_name`, `name`, `firstname`, `phone`, `email`, `password`, `id_lang`, `id_role`, `confirmation_token`) VALUES (?,?,?,?,?,?,?,?,?)');
        $statement->execute([
            $user->getCompanyName(),
            $user->getName(),
            $user->getFirstname(),
            $user->getPhone(),
            $user->getEmail(),
            $user->getPassword(),
            $user->getIdLang(),
            $user->getIdRole(),
            $user->getConfirmationToken()
        ]);
        if( $user->getIdRole() == 1){
            $id_user = $this->pdo->lastInsertId();
            try { // Send user confirmation mail
                //Server settings
                $mail = new PHPMailer(true);
                initSmtp($mail);

                //Recipients
                $mail->setFrom('test@test.com', 'Henry Schein'); // TODO modifier email admin
                $mail->addAddress($user->getEmail());

                //Content
                $mail->isHTML(true);
                $mail->Subject = Translation::of('accountConfirmation');
                $mail->Body    = Translation::of('accountConfirmationText').'</br> <a href="http://'.$_SERVER['HTTP_HOST'].'/views/user/confirm.php?id='.$id_user.'&token='.$user->getConfirmationToken().'">http://'.$_SERVER['HTTP_HOST'].'/views/user/confirm.php?id='.$id_user.'&token='.$user->getConfirmationToken().'</a>';

                $mail->send();
            } catch (Exception $e) {
                echo $mail->ErrorInfo;
            }
        }
    }

    /**
     * Update user in database
     * @param User $user
     * @return bool
     */
    public function update(\User\User $user): bool{
        $statement = $this->pdo->prepare('UPDATE `user` SET `company_name` = ?, `name` = ?, `firstname` = ?, `phone` = ?, `id_lang` = ?, `id_role` = ? WHERE `id` = ?');
        return $statement->execute([
            $user->getCompanyName(),
            $user->getName(),
            $user->getFirstname(),
            $user->getPhone(),
            $user->getIdLang(),
            $user->getIdRole(),
            $user->getId()
        ]);
    }

    /**
     * Update user email in database
     * @param User $user
     * @return bool
     */
    public function updateEmail(\User\User $user, array $datas): bool{
        $user->setEmail($datas['email']);
        $statement = $this->pdo->prepare('UPDATE `user` SET `email` = ? WHERE `id` = ?');
        return $statement->execute([
            $user->getEmail(),
            $user->getId()
        ]);
    }

    /**
     * Update user password in database
     * @param User $user
     * @return bool
     */
    public function updatePassword(\User\User $user, array $datas): bool{
        $user->setPassword(password_hash($datas['password'], PASSWORD_BCRYPT));
        $statement = $this->pdo->prepare('UPDATE `user` SET `password` = ?, `reset_token` = ?, `reset_at` = ? WHERE `id` = ?');
        return $statement->execute([
            $user->getPassword(),
            '',
            NULL,
            $user->getId()
        ]);
    }

    /**
     *  Delete user in database
     * @param User $user
     * @return bool
     */
    public function delete(\User\User $user): bool{
        $statement = $this->pdo->prepare('DELETE from `user` WHERE `id` = ?');
        return $statement->execute([
            $user->getId()
        ]);
    }

    /**
     * Confirm user email account
     * @param User $user
     * @return bool
     */
    public function confirmAccount(\User\User $user): bool{
        $statement = $this->pdo->prepare('UPDATE `user` SET `confirmation_token` = ?, `confirmed_at` = ? WHERE `id` = ?');
        return $statement->execute([
            '',
            date("Y-m-d H:i:s"),
            $user->getId()
        ]);
    }

    /**
     * TODO
     * @param array $datas
     * @return User
     * @throws \Exception
     */
    public function getConnexion (array $datas): \User\User {
         $user = $this->findByEmail($datas['email'], 'connexion');
         $user->setRememberToken(bin2hex(random_bytes(32)));
         if ($user) {
             if(password_verify($datas['password'], $user->getPassword())){
                 if(isset($datas['remember'])){
                     $statement = $this->pdo->prepare('UPDATE `user` SET `remember_token` = ? WHERE `id` = ?');
                     $statement->execute([
                         $user->getRememberToken(),
                         $user->getId()
                     ]);
                     setcookie('remember', $user->getId() . '//' . $user->getRememberToken() . sha1($user->getId() . 'HenrySchein'), time() + 60 * 60 * 24 * 7, '/'); // cookie sur 7 jours
                 }
                 return $user;
             }else{
                 throw new \Exception(Translation::of('errorConnexion'));
             }
         }else{
             throw new \Exception(Translation::of('errorConnexion'));
         }
     }

    public function forgotPassword (array $datas): void {
        $user = $this->findByEmail($datas['email'], 'password');
        if ($user) {
            $user->setResetToken(bin2hex(random_bytes(32)));
            $statement = $this->pdo->prepare('UPDATE `user` SET `reset_token` = ?, `reset_at` = ? WHERE `id` = ?');
            $statement->execute([
                $user->getResetToken(),
                date("Y-m-d H:i:s"),
                $user->getId()
            ]);
            try { // Send user forgot password mail
                //Server settings
                $mail = new PHPMailer(true);
                initSmtp($mail);

                //Recipients
                $mail->setFrom('test@test.com', 'Henry Schein'); // TODO modifier email admin
                $mail->addAddress($user->getEmail());

                //Content
                $mail->isHTML(true);
                $mail->Subject = Translation::of('resetPassword');
                $mail->Body    = Translation::of('resetPasswordText').'</br> <a href="http://'.$_SERVER['HTTP_HOST'].'/views/user/reset.php?id='.$user->getId().'&token='.$user->getResetToken().'">http://'.$_SERVER['HTTP_HOST'].'/views/user/reset.php?id='.$user->getId().'&token='.$user->getResetToken().'</a></br>'.Translation::of('durationLink');

                $mail->send();
            } catch (Exception $e) {
                echo $mail->ErrorInfo;
            }
        }else{
            throw new \Exception(Translation::of('errorForgottenPassword'));
        }
    }

    /**
     * Return list of events of user
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function findEvents (int $id): array {
        $statement = $this->pdo->query("SELECT * FROM `event` LEFT JOIN (`user_event`, `user`) ON (`event`.`id` = `user_event`.`id_event` AND `user_event`.`id_user` = `user`.`id`) WHERE `user`.`id` = $id");
        $result = $statement->fetchAll();
        if ($result === false) {
            throw new \Exception('Aucun résultat n\'a été trouvé');
        }
        return $result;
    }
}