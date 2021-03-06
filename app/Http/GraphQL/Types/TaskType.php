<?php

namespace App\Http\GraphQL\Types;

use GraphQL;
use App\Task;
use GraphQL\Type\Definition\Type;
use Nuwave\Lighthouse\Support\Definition\GraphQLType;
use Nuwave\Lighthouse\Support\Interfaces\RelayType;

class TaskType extends GraphQLType implements RelayType
{
    /**
     * Attributes of type.
     *
     * @var array
     */
    protected $attributes = [
        'name' => 'Task',
        'description' => 'A task assigned to a job.'
    ];

    /**
     * Get model by id.
     *
     * Note: When the root 'node' query is called, this method
     * will be used to resolve the type by providing the id.
     *
     * @param  mixed $id
     * @return mixed
     */
    public function resolveById($id)
    {
        return \App\Task::find($id);
    }

    /**
     * Type fields.
     *
     * @return array
     */
    public function fields()
    {
        return [
            'title' => [
                'type' => Type::string(),
                'description' => 'Title of the job task.',
            ],
            'users' => [
                'type' => GraphQL::type('taskUsers'),
                'args' => [
                    'count' => [
                        'type' => Type::nonNull(Type::int()),
                    ],
                    'page' => [
                        'type' => Type::int(),
                    ],
                ],
                'resolve' => function (Task $task, array $args) {
                    $count = $args['count'];
                    $page = $args['page'] ?? 1;

                    return $task->users()->paginate($count, ['*'], 'page', $page);
                }
            ],
        ];
    }
}
