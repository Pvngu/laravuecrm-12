<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Permission\IndexRequest;
use App\Models\Permission;

class PermissionController extends ApiBaseController
{
	protected $model = Permission::class;

	protected $indexRequest = IndexRequest::class;

	public function groupedPermissions()
	{
		$ignoredGroups = [];

		$cacheKey = 'grouped_permissions';
		$permissions = cache()->remember($cacheKey, 1800, function () use ($ignoredGroups) {
			return collect(
				Permission::all()
					->groupBy(function ($item) {
						$parts = explode('_', $item->name);
						// Group by first two parts if available, else first part
						// if (count($parts) >= 4) {
						// 	return implode('_', array_slice($parts, 0, 3));
						// } elseif (count($parts) >= 3) {
						// 	return implode('_', array_slice($parts, 0, 2));
						// }
						return $parts[0] ?? 'other';
					})
			)->except($ignoredGroups);
		});

		return response()->json($permissions);
	}
}
