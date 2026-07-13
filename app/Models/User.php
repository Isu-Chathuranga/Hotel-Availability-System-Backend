<?php
require_once __DIR__ . '/../Core/Model.php';

class User extends Model {
    protected string $table = 'users';

    public function findByEmail(string $email): ?array {
        return $this->findOneBy('email', $email);
    }

    public function emailExists(string $email): bool {
        return $this->findOneBy('email', $email) !== null;
    }

    public function createUser(string $name, string $email, string $passwordHash, string $phone, string $role): int {
        return $this->create([
            'name' => $name,
            'email' => $email,
            'password_hash' => $passwordHash,
            'phone' => $phone,
            'role' => $role
        ]);
    }

    public function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }

    public function getUserById(int $id): ?array {
        return $this->findById($id);
    }

    public function getOwnerEmail(int $ownerId): ?string {
        $user = $this->findById($ownerId);
        return $user ? $user['email'] : null;
    }
}
