/*
	Copyright (c) 2004-2006, The Dojo Foundation
	All Rights Reserved.

	Licensed under the Academic Free License version 2.1 or above OR the
	modified BSD license. For more information on Dojo licensing, see:

		http://dojotoolkit.org/community/licensing.shtml
*/


dojo.provide("dojo.lfx.toggle");
dojo.require("dojo.lfx.*");
dojo.lfx.toggle.plain={show:function(_1,_2,_3,_4){
dojo.html.show(_1);
if(dojo.lang.isFunction(_4)){
_4();
}
},hide:function(_5,_6,_7,_8){
dojo.html.hide(_5);
if(dojo.lang.isFunction(_8)){
_8();
}
}};
dojo.lfx.toggle.fade={show:function(_9,_a,_b,_c){
dojo.lfx.fadeShow(_9,_a,_b,_c).play();
},hide:function(_d,_e,_f,_10){
dojo.lfx.fadeHide(_d,_e,_f,_10).play();
}};
dojo.lfx.toggle.wipe={show:function(_11,_12,_13,_14){
dojo.lfx.wipeIn(_11,_12,_13,_14).play();
},hide:function(_15,_16,_17,_18){
dojo.lfx.wipeOut(_15,_16,_17,_18).play();
}};
dojo.lfx.toggle.explode={show:function(_19,_1a,_1b,_1c,_1d){
dojo.lfx.explode(_1d||{x:0,y:0,width:0,height:0},_19,_1a,_1b,_1c).play();
},hide:function(_1e,_1f,_20,_21,_22){
dojo.lfx.implode(_1e,_22||{x:0,y:0,width:0,height:0},_1f,_20,_21).play();
}};
