<?php

declare(strict_types=1);

namespace AlexManno\Drunk\Entity;

use AlexManno\Drunk\Core\Services\Hasher;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user")
 */
class User
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=150)
     */
    private $username;

    /**
     * @var string
     * @ORM\Column(type="string", length=150)
     */
    private $firstName;

    /**
     * @var string
     * @ORM\Column(type="string", length=150)
     */
    private $lastName;

    /**
     * @var string
     * @ORM\Column(type="string", length=150)
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=150)
     */
    private $password;

    /**
     * User constructor.
     *
     * @param string $username
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $password
     */
    public function __construct(
        string $username,
        string $firstName,
        string $lastName,
        string $email,
        string $password
    ) {
        $this->username = $username;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = Hasher::encrypt($password);
    }

    /**
     * @param array $data
     *
     * @return User
     */
    public static function fromArray(array $data): User
    {
        return new User(
            $data['username'],
            $data['firstName'],
            $data['lastName'],
            $data['email'],
            $data['password']
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
        ];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
