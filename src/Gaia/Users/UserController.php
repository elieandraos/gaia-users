<?php namespace Gaia\Users;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Gaia\Users\UserRequest;
use Gaia\Users\UserEditRequest;
use Gaia\Repositories\UserRepositoryInterface;
use App\User;
use Input;
use Redirect;
use Auth;
use App;


class UserController extends Controller {

	protected $userRepos, $roles, $authUser;

	/**
	 * Constructor: inject the user repository class to be used in all methods
	 * @return type
	 */
	public function __construct(UserRepositoryInterface $userReposInterface)
	{
		$this->userRepos   = $userReposInterface;
		$this->roles = [ 'Editor' => 'Editor', 'Administrator' => 'Administrator', 'Superadmin' => 'Superadmin'];
		//inject the authenticated user
		$this->authUser = Auth::user(); 
		//get the auth user rolename
		$rolename = $this->authUser->getRole()->name;
		$rolename = explode('-', $rolename);
		$this->authUserRolename = $rolename[0];
		
		if($this->authUserRolename != 'superadmin')
			App::abort(403, 'Access denied');
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$users = $this->userRepos->getAll();
		return view('admin.users.index', ["users" => $users]);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('admin.users.create', ['roles' => $this->roles, 'user_role' => null]);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(UserRequest $request)
	{
		$input = $request->all();
		$user = $this->userRepos->create($input); 
		return Redirect::route('admin.users.list');
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  User  $user
	 * @return Response
	 */
	public function edit(User $user)
	{
		$user_role = $this->userRepos->getUserRole($user);
		$user_role = $user_role->name;
		$user_role = explode('-', $user_role);
		return view('admin.users.edit', ["user" => $user, 'roles' => $this->roles, 'user_role' => ucwords($user_role[0])]);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(User $user, UserEditRequest $request)
	{
		$input = $request->all();
		$this->userRepos->update($user->id, $input);
		return Redirect::route('admin.users.list');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


	/**
	 * Manage user permissions
	 * @param type User $user 
	 * @return type
	 */
	public function permissions(User $user)
	{
		$role = $this->userRepos->getUserRole($user);
		$permissions = $this->userRepos->getPermissionsByRole($role);

		return view('admin.users.permissions', ['permissions' => $permissions, "user" => $user]);
	}


	/**
	 * Update user permissions
	 * @param type User $user 
	 * @return type
	 */
	public function updatePermissions(User $user)
	{
		$input = Input::all();
		$role = $this->userRepos->getUserRole($user);
		//detach existing permissions
		$role->detachPermissions($role->perms);
		//attach permissions to role
		if(isset($input['permission']))
		{
			$perms_id = $input['permission'];
			foreach($perms_id as $id)
			{
				$permission = $this->userRepos->findPermission($id);
				$role->attachPermission($permission);
			}
		}

		return Redirect::route('admin.users.permissions', $user->id);
		
	}

}
