<?php

class User {
    private $user_id;
    private $user_name;
    private $user_email;
    private $user_password;
    private $user_restaurant;
    private $user_status;
    private $user_role;
    private $verification_token;
    private $created_at;
    private $reset_token;
    private $verification_code;   // <-- nuevo
    private $code_expires_at;     // <-- nuevo

    // Constructor para inicializar usuario nuevo desde el registro
    public function __construct($user_name = null, $user_email = null, $user_password = null, $user_restaurant = null) {
        $this->user_name       = $user_name;
        $this->user_email      = $user_email;
        $this->user_password   = $user_password;
        $this->user_restaurant = $user_restaurant;
        $this->user_status     = 'pending'; // por defecto en espera de verificación
        $this->user_role       = 'user';    // todos los registrados son "user"
        $this->created_at      = date('Y-m-d H:i:s');
        $this->verification_code = null;
        $this->code_expires_at   = null;
    }

    // --- GETTERS ---
    public function getUserId() { return $this->user_id; }
    public function getUserName() { return $this->user_name; }
    public function getUserEmail() { return $this->user_email; }
    public function getUserPassword() { return $this->user_password; }
    public function getUserRestaurant() { return $this->user_restaurant; }
    public function getUserStatus() { return $this->user_status; }
    public function getUserRole() { return $this->user_role; }
    public function getVerificationToken() { return $this->verification_token; }
    public function getCreatedAt() { return $this->created_at; }
    public function getResetToken() { return $this->reset_token; }
    public function getVerificationCode() { return $this->verification_code; }
    public function getCodeExpiresAt() { return $this->code_expires_at; }

    // --- SETTERS ---
    public function setUserId($user_id) { $this->user_id = $user_id; }
    public function setUserName($user_name) { $this->user_name = $user_name; }
    public function setUserEmail($user_email) { $this->user_email = $user_email; }
    public function setUserPassword($user_password) { $this->user_password = $user_password; }
    public function setUserRestaurant($user_restaurant) { $this->user_restaurant = $user_restaurant; }
    public function setUserStatus($user_status) { $this->user_status = $user_status; }
    public function setUserRole($user_role) { $this->user_role = $user_role; }
    public function setVerificationToken($verification_token) { $this->verification_token = $verification_token; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }
    public function setResetToken($reset_token) { $this->reset_token = $reset_token; }
    public function setVerificationCode($verification_code) { $this->verification_code = $verification_code; }
    public function setCodeExpiresAt($code_expires_at) { $this->code_expires_at = $code_expires_at; }
}
