<?php
require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/User.php';

class AuthController extends Controller {
    private User $userModel;

    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
    }

    public function register(): void {
        $input = $this->getJsonInput();
        $name = trim($input['name'] ?? '');
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';
        $phone = trim($input['phone'] ?? '');
        $role = trim($input['role'] ?? 'traveler');

        $errors = [];
        if (empty($name)) $errors[] = "Name is required";
        if (empty($email)) $errors[] = "Email is required";
        if (empty($password)) $errors[] = "Password is required";
        if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters";
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format";
        if (!in_array($role, ['traveler', 'owner'])) $errors[] = "Role must be traveler or owner";

        if (!empty($errors)) {
            $this->json(["message" => "Validation failed", "errors" => $errors], 422);
        }

        if ($this->userModel->emailExists($email)) {
            $this->json(["message" => "Email already registered"], 409);
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $userId = $this->userModel->createUser($name, $email, $passwordHash, $phone, $role);

        $_SESSION['user_id'] = $userId;
        $_SESSION['role'] = $role;
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;

        $user = $this->userModel->getUserById($userId);
        unset($user['password_hash']);

        $this->json(["message" => "Registration successful", "user" => $user], 201);
    }

    public function login(): void {
        $input = $this->getJsonInput();
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->json(["message" => "Email and password are required"], 422);
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user || !$this->userModel->verifyPassword($password, $user['password_hash'])) {
            $this->json(["message" => "Invalid email or password"], 401);
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];

        unset($user['password_hash']);
        $this->json(["message" => "Login successful", "user" => $user]);
    }

    public function logout(): void {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        $this->json(["message" => "Logged out successfully"]);
    }

    public function checkSession(): void {
        if (!$this->isLoggedIn()) {
            $this->json(["message" => "Not authenticated"], 401);
        }

        $user = $this->userModel->getUserById($this->getUserId());
        if (!$user) {
            session_destroy();
            $this->json(["message" => "User not found"], 401);
        }

        unset($user['password_hash']);
        $this->json(["user" => $user]);
    }
}
