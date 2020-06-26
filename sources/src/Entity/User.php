<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\Actions\User\UpdateProfile;
use App\Controller\Actions\User\UserItem;
use App\DBAL\Types\UserRole;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     attributes={
 *          "normalization_context"={"groups"={"user:read"}},
 *     },
 *     collectionOperations={
 *     },
 *     itemOperations={
 *         "get_profile"={
 *             "method"="GET",
 *             "read"=false,
 *             "path"="/users/profile",
 *             "controller"=UserItem::class,
 *             "normalization_context"={"groups"={"user:read"}},
 *             "openapi_context"={
 *                 "summary"="Retreive the current User resource.",
 *                 "parameters"={},
 *             },
 *         },
 *         "update_profile"={
 *             "method"="PUT",
 *             "read"=false,
 *             "path"="/users/profile",
 *             "controller"=UpdateProfile::class,
 *             "normalization_context"={"groups"={"user:read"}},
 *             "denormalization_context"={"groups"={"user:profile:write"}},
 *             "openapi_context"={
 *                 "summary"="Update the current User resource.",
 *                 "parameters"={},
 *             },
 *         },
 *         "get"={
 *              "method"="GET",
 *              "access_control"="is_granted('ROLE_USER')",
 *         },
 *     }
 * )
 * @ORM\EntityListeners({"App\EntityListener\HashPasswordListener"})
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="t_user")
 */
class User implements UserInterface
{
    /**
     * @var string
     *
     * @Assert\Length(min="6", max="255")
     *
     * @Groups({"user:password"})
     */
    protected $plainPassword;

    /**
     * @Groups({"user:read", "user:profile:read"})
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"user:read", "user:profile:read"})
     *
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @Groups({"user:read", "user:profile:write"})
     *
     * @ORM\Column(name="first_name", type="string", length=180)
     */
    private $firstName;

//    * @DoctrineAssert\Enum(entity="App\DBAL\Types\UserRoleType")
    /**
     * @Groups({"user:read", "user:profile:write"})
     *
     * @ORM\Column(name="last_name", type="string", length=180)
     */
    private $lastName;

    /**
     * @ORM\Column(type="json")
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="string",
     *             "enum"={"ROLE_ADMIN", "ROLE_USER"},
     *             "example"="ROLE_USER",
     *             "default"="ROLE_USER"
     *         }
     *     }
     * )
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var string
     *
     * @Assert\Length(max="255")
     *
     * @ORM\Column(name="recovery_password_token", type="string", length=255, nullable=true)
     */
    private $recoveryPasswordToken;

    /**
     * @var DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime", nullable=false, options={"default": "CURRENT_TIMESTAMP"})
     */
    private $createdAt;

    /**
     * @var DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @Gedmo\Timestampable(on="create")
     * @Gedmo\Timestampable(on="change")
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    public function getRole(): ?string
    {
        return count($this->getRoles()) ? current($this->getRoles()) : null;
    }

    public function getRoles(): ?array
    {
        $roles = $this->roles;

        if (!in_array(UserRole::ROLE_USER, $roles)) {
            $roles[] = UserRole::ROLE_USER;
        }

        return $this->roles;
    }

    /**
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function setRole(string $role): self
    {
        $this->setRoles([$role]);

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @return $this
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @return $this
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return $this
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @return $this
     */
    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return string
     */
    public function getRecoveryPasswordToken(): ?string
    {
        return $this->recoveryPasswordToken;
    }

    /**
     * @param string $recoveryPasswordToken
     */
    public function setRecoveryPasswordToken(?string $recoveryPasswordToken): User
    {
        $this->recoveryPasswordToken = $recoveryPasswordToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): User
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function __toString()
    {
        return $this->getFullName();
    }

    /**
     * Get user's full name
     */
    public function getFullName(): string
    {
        if ($this->firstName || $this->lastName) {
            return trim(sprintf('%s %s', $this->firstName, $this->lastName));
        }

        return '';
    }
}
