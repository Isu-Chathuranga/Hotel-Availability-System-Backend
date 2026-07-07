<?php
namespace App\Middleware;

use App\Views\JsonView;

class AuthMiddleware
{
    public static function requireLogin(): void
    {
        if (!isset($_SESSION['user_id'])) {
            JsonView::error("Unauthorized. Please login first.", 401);
        }
    }

    public static function requireOwner(): void
    {
        self::requireLogin();
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'owner') {
            JsonView::error("Access denied. Owner role required.", 403);
        }
    }

    public static function requireAdmin(): void
    {
        self::requireLogin();
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            JsonView::error("Access denied. Admin role required.", 403);
        }
    }

    public static function getUserId(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }

    public static function getRole(): ?string
    {
        return $_SESSION['role'] ?? null;
    }
}
