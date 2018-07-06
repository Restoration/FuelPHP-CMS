jQuery(document).ready(function($){

	/*
	* Model
	*
	**/
	var Task = Backbone.Model.extend({
		// Validate
		validate: function(attrs){
			if(_.isEmpty(attrs.title)){
				return 'Task name must be specified';
			}
		},
		initialize: function(){
			this.on('invalid',function(model,error){
				$('#error').html(error);
			})
		}
	});

	/*
	* Collection
	*
	**/
	var Tasks = Backbone.Collection.extend({
		localStorage: new Backbone.LocalStorage("task"),
		model: Task
	});

	/*
	* View
	*
	**/
	var TaskView = Backbone.View.extend({
		tagName: 'li',
		initialize: function(){
			// also delete model
			this.model.on('destory',this.remove,this);
		},
		events: {
			'click .delete': 'destroy'
		},
		destroy: function(){
			if(confirm('Do you want to delete it?')){
				this.model.destroy();
			}
		},
		remove: function(){
			this.$el.remove();
		},
		template: _.template($('#task-template').html()),
		render: function(){
			// Pass model to template
			var template = this.template(this.model.toJSON());
			this.$el.html(template);
			return this;
		}
	});

	var TasksView = Backbone.View.extend({
		tagName: 'ul',
		initialize: function(){
			this.collection.on('add',this.addNew,this);
		},
		addNew: function(task){
			var taskView = new TaskView({model: task});
			this.$el.append(taskView.render().el);
		},
		render: function(){
			this.collection.each(function(task){
				var taskView = new TaskView({model: task});
				this.$el.append(taskView.render().el);
			},this);
			return this;
		}
	});

	var AddTaskView = Backbone.View.extend({
		el: '#addTask',
		events: {
			'submit': 'submit'
		},
		submit: function(e){
			e.preventDefault();
			var task = new Task();
			if(task.set({title: $('#title').val()},{validate: true})){
				this.collection.add(task);
			}
		}
	});




// js/app.js
window.App = {};
var initializeNotes = function() {
  var noteCollection = new App.NoteCollection([{
	// 作成したモデルはローカルストレージに保存する
	Tasks.each(function(note) {
		task.save();
	});
  return noteCollection.models;
};

$(function() {
	App.noteCollection = new App.NoteCollection();
	App.mainContainer = new App.Container({
    el:'#main-container'
  });

  // 初期化処理を追加する
  App.headerContainer = new App.Container({
    el:'#header-container'
  });

  // NoteCollectionコレクションのデータを受信する
  // (Backbone.LocalStorageを使用しているので
  // ブラウザのローカルストレージから読み込む
  App.noteCollection.fetch().then(function(notes){

    // もし読み込んだデータが空であれば
    // ダミーデータでコレクションの中身を上書きする
    if(notes.length === 0) {
      var models = initializeNotes();
      App.noteCollection.reset(models);
    }

    // ルータの初期化と履歴管理の開始
    App.router = new App.Router();
    Backbone.history.start();
  });

});



	var tasks = new Tasks();
	var tasksView = new TasksView({collection: tasks});
	var addTaskView = new AddTaskView({collection: tasks});
	$('#tasks').html(tasksView.render().el);
});

