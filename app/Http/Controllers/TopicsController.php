<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
use App\Models\Category;
use Auth;

class TopicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function index(Request $request, Topic $topic)
    {
        // 在话题控制器中链式调用定义的方法 withOrder()
        // $request->order 是获取 URI http://larabbs.test/topics?order=recent 中的 order 参数。
        $topics = $topic->withOrder($request->order)->paginate(20);
        return view('topics.index', compact('topics'));
    }

    public function show(Topic $topic)
    {
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
        return redirect()->route('topics.show', $topic->id)->with('message', '创建成功');
	}

	public function edit(Topic $topic)
	{
        $this->authorize('update', $topic);
		return view('topics.create_and_edit', compact('topic'));
	}

	public function update(TopicRequest $request, Topic $topic)
	{
		$this->authorize('update', $topic);
		$topic->update($request->all());

		return redirect()->route('topics.show', $topic->id)->with('message', 'Updated successfully.');
	}

	public function destroy(Topic $topic)
	{
		$this->authorize('destroy', $topic);
		$topic->delete();

		return redirect()->route('topics.index')->with('message', 'Deleted successfully.');
	}
}