<?php

declare(strict_types=1);

/**
 * Basit Kullanıcı Sınıfı
 */
class User
{
    private string $name;
    private string $email;
    private int $age;

    public function __construct(string $name, string $email, int $age)
    {
        $this->name = $name;
        $this->email = $email;
        $this->age = $age;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Geçersiz email adresi');
        }
        $this->email = $email;
    }

    public function setAge(int $age): void
    {
        if ($age < 0 || $age > 150) {
            throw new InvalidArgumentException('Yaş 0-150 arasında olmalıdır');
        }
        $this->age = $age;
    }

    public function isAdult(): bool
    {
        return $this->age >= 18;
    }

    public function getInfo(): string
    {
        return sprintf(
            'İsim: %s, Email: %s, Yaş: %d, Durum: %s',
            $this->name,
            $this->email,
            $this->age,
            $this->isAdult() ? 'Yetişkin' : 'Reşit değil'
        );
    }
}

/**
 * Kullanıcı Yöneticisi Sınıfı
 */
class UserManager
{
    /** @var User[] */
    private array $users = [];

    public function addUser(User $user): void
    {
        $this->users[] = $user;
    }

    public function getUserByEmail(string $email): ?User
    {
        foreach ($this->users as $user) {
            if ($user->getEmail() === $email) {
                return $user;
            }
        }
        return null;
    }

    /**
     * @return User[]
     */
    public function getAdultUsers(): array
    {
        return array_filter($this->users, function (User $user): bool {
            return $user->isAdult();
        });
    }

    public function getUserCount(): int
    {
        return count($this->users);
    }

    /**
     * @return User[]
     */
    public function getAllUsers(): array
    {
        return $this->users;
    }
}

try {
    $userManager = new UserManager();

    $user1 = new User('Ahmet Yılmaz', 'ahmet@example.com', 25);
    $user2 = new User('Ayşe Kaya', 'ayse@example.com', 17);
    $user3 = new User('Mehmet Demir', 'mehmet@example.com', 30);

    $userManager->addUser($user1);
    $userManager->addUser($user2);
    $userManager->addUser($user3);

    echo "Toplam kullanıcı sayısı: " . $userManager->getUserCount() . "\n";
    echo "Yetişkin kullanıcı sayısı: " . count($userManager->getAdultUsers()) . "\n\n";

    echo "Tüm kullanıcılar:\n";
    foreach ($userManager->getAllUsers() as $user) {
        echo $user->getInfo() . "\n";
    }

    echo "\nEmail ile kullanıcı arama:\n";
    $foundUser = $userManager->getUserByEmail('ahmet@example.com');
    if ($foundUser) {
        echo "Bulunan kullanıcı: " . $foundUser->getInfo() . "\n";
    }

} catch (InvalidArgumentException $e) {
    echo "Hata: " . $e->getMessage() . "\n";
}