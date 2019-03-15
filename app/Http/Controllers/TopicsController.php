<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
use App\Models\Category;
use Auth;
use App\Handlers\ImageUploadHandler;

class TopicsController extends Controller
{
    public function __construct()
    {
         // 对除了 index() 和 show() 以外的方法使用 auth 中间件进行认证
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function index(Request $request, Topic $topic)
    {
        // 在话题控制器中链式调用定义的方法 withOrder()
        // $request->order 是获取 URI http://larabbs.test/topics?order=recent 中的 order 参数。
        $topics = $topic->withOrder($request->order)->paginate(20);
        return view('topics.index', compact('topics'));
    }

    public function show(Request $request, Topic $topic)
    {
        // URL 矫正
        if ( ! empty($topic->slug) && $topic->slug != $request->slug) {
            // 301 永久重定向到正确的 URL 上
            return redirect($topic->link(), 301);
        }
        return view('topics.show', compact('topic'));
    }

	public function create(Topic $topic)
	{
        $categories = Category::all();
        // 将所有的分类读取赋值给变量 $categories ，并传入模板中
        return view('topics.create_and_edit', compact('topic', 'categories'));
	}

	public function store(TopicRequest $request, Topic $topic)
	{
	    // store() 方法的第二个参数，会创建一个空白的 $topic 实例
        // $request->all() 获取所有用户的请求数据数组，如 ['title' => '标题', 'body' => '内容', ... ]；
	    // fill 方法会将传参的键值数组填充到模型的属性中，如以上数组，$topic->title 的值为 标题；
		$topic = $topic->fill($request->all());
		// Auth::id() 获取到的是当前登录的 ID
        $topic->user_id = Auth::id();
        // $topic->save() 保存到数据库中
        $topic->save();
        return redirect()->to($topic->link())->with('success', '创建成功');
	}

	public function edit(Topic $topic)
	{
        // 授权策略调用
        $this->authorize('update', $topic);
        $categories = Category::all();
		return view('topics.create_and_edit', compact('topic', 'categories'));
	}

	public function update(TopicRequest $request, Topic $topic)
	{
	    // 授权策略调用
		$this->authorize('update', $topic);
		$topic->update($request->all());

		return redirect()->to($topic->link())->with('success', '更新成功');
	}

	public function destroy(Topic $topic)
	{
		$this->authorize('destroy', $topic);
		$topic->delete();

		return redirect()->route('topics.index')->with('success', '删除成功');
	}

    public function uploadImage(Request $request, ImageUploadHandler $uploader)
    {
        // 初始化返回数据，默认是失败的
        $data = [
            'success'   => false,
            'msg'       => '上传失败!',
            'file_path' => ''
        ];
        // 判断是否有上传文件，并赋值给 $file
        if ($file = $request->upload_file) {
            // 保存图片到本地
            $result = $uploader->save($request->upload_file, 'topics', \Auth::id(), 1024);
            // 图片保存成功的话
            if ($result) {
                $data['file_path'] = $result['path'];
                $data['msg']       = "上传成功!";
                $data['success']   = true;
            }
        }
        return $data;
    }
}