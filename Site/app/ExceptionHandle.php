<?php
namespace app;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\Response;
use Throwable;

/**
 * 应用异常处理类
 */
class ExceptionHandle extends Handle
{
    /**
     * 不需要记录信息（日志）的异常类列表
     * @var array
     */
    protected $ignoreReport = [
        HttpException::class,
        HttpResponseException::class,
        ModelNotFoundException::class,
        DataNotFoundException::class,
        ValidateException::class,
    ];

    /**
     * 记录异常信息（包括日志或者其它方式记录）
     *
     * @access public
     * @param  Throwable $exception
     * @return void
     */
    public function report(Throwable $exception): void
    {
        // 使用内置的方式记录异常日志
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @access public
     * @param \think\Request   $request
     * @param Throwable $e
     * @return Response
     */
    public function render($request, Throwable $e) : Response
    {
        // 添加自定义异常处理机制

        // 页面不存在
        if ($e instanceof HttpException && $e->getStatusCode() == 404) {
            if($request->isAjax()) {
                return Response::create(['msg' => '抱歉，你访问的页面不存在', 'code' => 404, 'data' => []], 'json');
            } else {
                return view(root_path() . 'public/statics/404.html', ['e' => $e]);
            }
        }

        // 权限不足
        if ($e instanceof HttpException && $e->getStatusCode() == 403) {
            if($request->isAjax()) {
                return Response::create(['msg' => '抱歉，你的权限不足', 'code' => 403, 'data' => []], 'json');
            } else {
                return view(root_path() . 'public/statics/403.html', ['e' => $e]);
            }
        }

        // 其他错误交给系统处理
        return parent::render($request, $e);
    }
}
