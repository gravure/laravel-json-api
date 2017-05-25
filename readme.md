# Laravel JSON api

[![codecov](https://codecov.io/gh/gravure/laravel-json-api/branch/master/graph/badge.svg)](https://codecov.io/gh/gravure/laravel-json-api)

This package assists at generating [JSON API v1](http://jsonapi.org/) compatible output.

## Installation

```bash
composer require gravure/laravel-json-api
```

Register the service provider in your `config/app.php` under `providers`:

```php
    Gravure\Api\Providers\ApiProvider::class,
```

## Usage

An important element of this package is the `ReplacesRequest` middleware that is automatically
active for any request that accepts `application/vnd.api+json` content. The inject Request
object provides a set of helper subclasses, for instance for handling pagination or filtering.

Aside from that, by extending the abstract ResourceController or Controller one can easily
generate Json documents based on the specification. The ResourceController in fact provides a
complete Laravel ResourceController implementation, where it only needs a Repository to process
database specific logic.

## Example

This is a simple example with a `Post` having one or more `Comment` and a single author `User`.

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    public function author()
    {
        return $this->belongsTo(User::class);
    }
}
```

```php
<?php

namespace App\Serializers;

use Gravure\Api\Resources\Collection;
use Tobscure\JsonApi\AbstractSerializer;
use Tobscure\JsonApi\Relationship;
use Tobscure\JsonApi\Resource;

class PostSerializer extends AbstractSerializer
{
    protected $type = 'posts';

    public function getId($model)
    {
        return $model->id;
    }

    public function getAttributes($model, array $fields = null)
    {
        return [
            'title'   => $model->title,
            'content' => $model->content,
        ];
    }

    public function comments($model)
    {
        return new Relationship(new Collection($model->comments, new CommentSerializer));
    }

    public function author($model)
    {
        return new Relationship(new Resource($model->user, new UserSerializer));
    }
}
```

```php
<?php

namespace App\Repositories;

use App\Post;
use Gravure\Api\Contracts\Repository;
use Gravure\Api\Http\Request;
use Illuminate\Database\Eloquent\Model;

class PostRepository implements Repository
{
    public function query()
    {
        return Post::query();
    }

    public function find(int $id): ?Model
    {
        return Post::find($id);
    }

    public function update(Model $model, Request $request): ?Model
    {
        $model->title = $request->get('title');
        $model->content = $request->get('content');
        $model->save();
        
        return $model;
    }

    public function store(Request $request): ?Model
    {
        $model = new Post;
        $model->title = $request->get('title');
        $model->content = $request->get('content');
        $model->save();
        
        return $model;
    }

    public function delete(int $id): bool
    {
        $model = $this->find($id);
        
        if ($model) {
            return $model->delete();
        }
        
        return false;
    }
}
```

```php
<?php

namespace App\Http\Controllers;

use App\Repositories\PostRepository;
use App\Serializers\PostSerializer;
use Gravure\Api\Contracts\Repository;
use Gravure\Api\Controllers\ResourceController;
use Gravure\Api\Http\Request;

class PostController extends ResourceController
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->serializer = new PostSerializer();
    }

    protected function repository(): Repository
    {
        return new PostRepository();
    }
}
```
