/*
	Copyright (c) 2004-2006, The Dojo Foundation
	All Rights Reserved.

	Licensed under the Academic Free License version 2.1 or above OR the
	modified BSD license. For more information on Dojo licensing, see:

		http://dojotoolkit.org/community/licensing.shtml
*/


dojo.provide("dojo.widget.TreeSelector");
dojo.require("dojo.widget.HtmlWidget");
dojo.widget.defineWidget("dojo.widget.TreeSelector",dojo.widget.HtmlWidget,function(){
this.eventNames={};
this.listenedTrees=[];
},{widgetType:"TreeSelector",selectedNode:null,dieWithTree:false,eventNamesDefault:{select:"select",destroy:"destroy",deselect:"deselect",dblselect:"dblselect"},initialize:function(){
for(var _1 in this.eventNamesDefault){
if(dojo.lang.isUndefined(this.eventNames[_1])){
this.eventNames[_1]=this.widgetId+"/"+this.eventNamesDefault[_1];
}
}
},destroy:function(){
dojo.event.topic.publish(this.eventNames.destroy,{source:this});
return dojo.widget.HtmlWidget.prototype.destroy.apply(this,arguments);
},listenTree:function(_2){
dojo.event.topic.subscribe(_2.eventNames.titleClick,this,"select");
dojo.event.topic.subscribe(_2.eventNames.iconClick,this,"select");
dojo.event.topic.subscribe(_2.eventNames.collapse,this,"onCollapse");
dojo.event.topic.subscribe(_2.eventNames.moveFrom,this,"onMoveFrom");
dojo.event.topic.subscribe(_2.eventNames.removeNode,this,"onRemoveNode");
dojo.event.topic.subscribe(_2.eventNames.treeDestroy,this,"onTreeDestroy");
this.listenedTrees.push(_2);
},unlistenTree:function(_3){
dojo.event.topic.unsubscribe(_3.eventNames.titleClick,this,"select");
dojo.event.topic.unsubscribe(_3.eventNames.iconClick,this,"select");
dojo.event.topic.unsubscribe(_3.eventNames.collapse,this,"onCollapse");
dojo.event.topic.unsubscribe(_3.eventNames.moveFrom,this,"onMoveFrom");
dojo.event.topic.unsubscribe(_3.eventNames.removeNode,this,"onRemoveNode");
dojo.event.topic.unsubscribe(_3.eventNames.treeDestroy,this,"onTreeDestroy");
for(var i=0;i<this.listenedTrees.length;i++){
if(this.listenedTrees[i]===_3){
this.listenedTrees.splice(i,1);
break;
}
}
},onTreeDestroy:function(_5){
this.unlistenTree(_5.source);
if(this.dieWithTree){
this.destroy();
}
},onCollapse:function(_6){
if(!this.selectedNode){
return;
}
var _7=_6.source;
var _8=this.selectedNode.parent;
while(_8!==_7&&_8.isTreeNode){
_8=_8.parent;
}
if(_8.isTreeNode){
this.deselect();
}
},select:function(_9){
var _a=_9.source;
var e=_9.event;
if(this.selectedNode===_a){
if(e.ctrlKey||e.shiftKey||e.metaKey){
this.deselect();
return;
}
dojo.event.topic.publish(this.eventNames.dblselect,{node:_a});
return;
}
if(this.selectedNode){
this.deselect();
}
this.doSelect(_a);
dojo.event.topic.publish(this.eventNames.select,{node:_a});
},onMoveFrom:function(_c){
if(_c.child!==this.selectedNode){
return;
}
if(!dojo.lang.inArray(this.listenedTrees,_c.newTree)){
this.deselect();
}
},onRemoveNode:function(_d){
if(_d.child!==this.selectedNode){
return;
}
this.deselect();
},doSelect:function(_e){
_e.markSelected();
this.selectedNode=_e;
},deselect:function(){
var _f=this.selectedNode;
this.selectedNode=null;
_f.unMarkSelected();
dojo.event.topic.publish(this.eventNames.deselect,{node:_f});
}});
