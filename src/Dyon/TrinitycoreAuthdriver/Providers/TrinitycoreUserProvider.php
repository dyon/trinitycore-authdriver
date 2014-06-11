<?php namespace Dyon\TrinitycoreAuthdriver\Providers;

use Illuminate\Auth\UserProviderInterface;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\GenericUser;
use Illuminate\Hashing\HasherInterface;

class TrinitycoreUserProvider implements UserProviderInterface {

    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function retrieveById($identifier)
    {
        return $this->createModel()->newQuery()->find($identifier);
    }

    public function retrieveByToken($identifier, $token)
    {
        $model = $this->createModel();

        return $model->newQuery()
            ->where($model->getKeyName(), $identifier)
            ->where($model->getRememberTokenName(), $token)
            ->first();
    }

    public function updateRememberToken(UserInterface $user, $token)
    {
        $user->setAttribute($user->getRememberTokenName(), $token);

        $user->save();
    }

    public function retrieveByCredentials(array $credentials)
    {
        $query = $this->createModel()->newQuery();

        foreach ($credentials as $key => $value)
        {
            if ( ! str_contains($key, 'password') ) $query->where($key, $value);
        }

        return $query->first();
    }

    public function validateCredentials(UserInterface $user, array $credentials)
    {
        // Here we shouldn't convert to upper case but this is how TrinityCore login system works
        $hashedPassword = sha1(strtoupper($credentials['username'] . ':' . $credentials['password']));

        return strtoupper($user->sha_pass_hash) === strtoupper($hashedPassword);
    }

    public function createModel()
    {
        $class = '\\'.ltrim($this->model, '\\');

        return new $class;
    }
}


