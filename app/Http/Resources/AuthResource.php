<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $permission_list = $this->get_permission_list($this->role);
        $access_token = $this->createToken('web_login', $permission_list)->plainTextToken;
        return [
            'user'          => new UserResource($this),
            'access_token'  => $access_token,
            'access_role'   => $this->role,
            'access_permission' => $this->get_permission_json($permission_list),
        ];
    }

    /**
     * Customize the outgoing response for the resource.
     *
     * @param  \Illuminate\Http\Request
     * @param  \Illuminate\Http\Response
     * @return void
     */
    public function withResponse($request, $response)
    {
        /**
         * Not all prerequisites were met.
         */
        $response->setStatusCode(200, 'Autharization susscess');
    }

    public function with($request)
    {
        return [
            'success' => true
        ];
    }

    private function get_permission_list($role_name)
    {
        $role = Role::findByName($role_name);
        return $role->permissions->pluck('name')->toArray();
    }

    private function get_permission_json($permission_list)
    {
        $arr_p = [];

        foreach ($permission_list as $key => $item) {
            $obj_permitions = explode(".", $item);
            $first = array_shift($obj_permitions);

            if (!array_key_exists($first, $arr_p)) {
                $arr_p[$first] = [];
            }

            $arr_p[$first] = array_merge($arr_p[$first], $obj_permitions);
        }

        return $arr_p;
    }
}
