<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ClearanceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->hasPermissionTo('Administer roles & permissions'))
        {
            return $next($request);  // 管理员具备所有权限
        }

        /********************************账户/资金管理*******************************/
        if ($request->is('Accounts/create')) // 发布权限
        {
            if (!Auth::user()->hasPermissionTo('Create Ac'))
            {
                abort('401');
            }else {
                return $next($request);
            }
        }

        if ($request->is('Accounts/edit')) // 编辑权限
        {
            if (!Auth::user()->hasPermissionTo('Edit Ac')) {
                abort('401');
            } else {
                return $next($request);
            }
        }

        if ($request->isMethod('Accounts/del')) // 删除权限
        {
            if (!Auth::user()->hasPermissionTo('Delete Ac')) {
                abort('401');
            }else{
                return $next($request);
            }
        }
        //资金管理
        if($request->is('Accounts/capital')){
            if(!Auth::user()->hasPermissionTo('Select Ac')){
                abort('401');
            }else{
                return $next($request);
            }
        }
        //收支流水详情
        if($request->is('Accounts/stream')){
            if(!Auth::user()->hasPermissionTo('Str Ac')){
                abort('401');
            }else{
                return $next($request);
            }
        }
        //转账流水详情
        if($request->is('Transfers/Transfers_sel')){
            if(!Auth::user()->hasPermissionTo('Sel Tr')){
                abort('401');
            }else{
                return $next($request);
            }
        }
        //转账
        if($request->is('Transfers/create')){
            if(!Auth::user()->hasPermissionTo('Create Tr')){
                abort('401');
            }else{
                return $next($request);
            }
        }

        /********************************大类*******************************/
        if ($request->is('Types/create')) // 发布权限
        {
            if (!Auth::user()->hasPermissionTo('Create Ty'))
            {
                abort('401');
            }
            else {
                return $next($request);
            }
        }

        if ($request->is('Types/edit')) // 编辑权限
        {
            if (!Auth::user()->hasPermissionTo('Edit Ty')) {
                abort('401');
            } else {
                return $next($request);
            }
        }

        if ($request->isMethod('Types/del')) // 删除权限
        {
            if (!Auth::user()->hasPermissionTo('Delete Ty')) {
                abort('401');
            }
            else
            {
                return $next($request);
            }
        }

        /********************************小类*******************************/
        if ($request->is('Xtypes/create')) // 发布权限
        {
            if (!Auth::user()->hasPermissionTo('Create Xl'))
            {
                abort('401');
            }
            else {
                return $next($request);
            }
        }

        if ($request->is('Xtypes/edit')) // 编辑权限
        {
            if (!Auth::user()->hasPermissionTo('Edit Xl')) {
                abort('401');
            } else {
                return $next($request);
            }
        }

        if ($request->isMethod('Xtypes/del')) // 删除权限
        {
            if (!Auth::user()->hasPermissionTo('Delete Xl')) {
                abort('401');
            }
            else
            {
                return $next($request);
            }
        }

        /********************************客户*******************************/
        if ($request->is('Customer/create')) // 发布权限
        {
            if (!Auth::user()->hasPermissionTo('Create Cu'))
            {
                abort('401');
            }
            else {
                return $next($request);
            }
        }

        if ($request->is('Customer/edit')) // 编辑权限
        {
            if (!Auth::user()->hasPermissionTo('Edit Cu')) {
                abort('401');
            } else {
                return $next($request);
            }
        }

        if ($request->isMethod('Customer/del')) // 删除权限
        {
            if (!Auth::user()->hasPermissionTo('Delete Cu')) {
                abort('401');
            }
            else
            {
                return $next($request);
            }
        }

        /********************************员工*******************************/
        if ($request->is('Staffs/create')) // 发布权限
        {
            if (!Auth::user()->hasPermissionTo('Create St'))
            {
                abort('401');
            }
            else {
                return $next($request);
            }
        }

        if ($request->is('Staffs/edit')) // 编辑权限
        {
            if (!Auth::user()->hasPermissionTo('Edit St')) {
                abort('401');
            } else {
                return $next($request);
            }
        }

        if ($request->isMethod('Staffs/del')) // 删除权限
        {
            if (!Auth::user()->hasPermissionTo('Delete St')) {
                abort('401');
            }
            else
            {
                return $next($request);
            }
        }

        /********************************项目*******************************/
        if ($request->is('Projects/create')) // 发布权限
        {
            if (!Auth::user()->hasPermissionTo('Create Pr'))
            {
                abort('401');
            }
            else {
                return $next($request);
            }
        }

        if ($request->is('Projects/edit')) // 编辑权限
        {
            if (!Auth::user()->hasPermissionTo('Edit Pr')) {
                abort('401');
            } else {
                return $next($request);
            }
        }

        if ($request->isMethod('Projects/del')) // 删除权限
        {
            if (!Auth::user()->hasPermissionTo('Delete Pr')) {
                abort('401');
            }
            else
            {
                return $next($request);
            }
        }

        /********************************收支流水账*******************************/
        if ($request->is('Incomes/create')) // 发布权限
        {
            if (!Auth::user()->hasPermissionTo('Create In'))
            {
                abort('401');
            }
            else {
                return $next($request);
            }
        }

        if ($request->is('Incomes/edit')) // 编辑权限
        {
            if (!Auth::user()->hasPermissionTo('Edit In')) {
                abort('401');
            } else {
                return $next($request);
            }
        }

        if ($request->isMethod('Incomes/del')) // 删除权限
        {
            if (!Auth::user()->hasPermissionTo('Delete In')) {
                abort('401');
            }
            else
            {
                return $next($request);
            }
        }

        /********************************收付流水账*******************************/
        if ($request->is('Payments/create')) // 发布权限
        {
            if (!Auth::user()->hasPermissionTo('Create Pa'))
            {
                abort('401');
            }
            else {
                return $next($request);
            }
        }

        if ($request->is('Payments/edit')) // 编辑权限
        {
            if (!Auth::user()->hasPermissionTo('Edit Pa')) {
                abort('401');
            } else {
                return $next($request);
            }
        }

        if ($request->isMethod('Payments/del')) // 删除权限
        {
            if (!Auth::user()->hasPermissionTo('Delete Pa')) {
                abort('401');
            }
            else
            {
                return $next($request);
            }
        }



        return $next($request);
    }
}
