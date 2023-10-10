   1. // vim: sw=4:ts=4:nu:nospell:fdc=4
   2. /*global Ext, Example */
   3. /**
   4. * Drag and Drop Grids Example application
   5. *
   6. * @author Ing. Jozef Sakáloš
   7. * @copyright (c) 2008, by Ing. Jozef Sakáloš
   8. * @sponsor Bernhard Schulz http://www.schubec.com
   9. * @date 2. April 2008
  10. * @version $Id: ddgrids.js 156 2009-09-19 23:31:02Z jozo $
  11. *
  12. * @license ddgrids.js is licensed under the terms of the Open Source
  13. * LGPL 3.0 license. Commercial use is permitted to the extent that the
  14. * code/component(s) do NOT become part of another Open Source or Commercially
  15. * licensed development library or toolkit without explicit permission.
  16. *
  17. * License details: http://www.gnu.org/licenses/lgpl.html
  18. */
  19.  
  20. Ext.ns('Example');
  21.  
  22. // {{{
  23. Example.GridDropZone = function(grid, config) {
  24. this.grid = grid;
  25. Example.GridDropZone.superclass.constructor.call(this, grid.view.scroller.dom, config);
  26. };
  27. Ext.extend(Example.GridDropZone, Ext.dd.DropZone, {
  28.  
  29. onContainerOver:function(dd, e, data) {
  30. return dd.grid !== this.grid ? this.dropAllowed : this.dropNotAllowed;
  31. } // eo function onContainerOver
  32.  
  33. ,onContainerDrop:function(dd, e, data) {
  34. if(dd.grid !== this.grid) {
  35. this.grid.store.add(data.selections);
  36. Ext.each(data.selections, function(r) {
  37. dd.grid.store.remove(r);
  38. });
  39. this.grid.onRecordsDrop(dd.grid, data.selections);
  40. return true;
  41. }
  42. else {
  43. return false;
  44. }
  45. } // eo function onContainerDrop
  46. ,containerScroll:true
  47.  
  48. });
  49. // }}}
  50. // {{{
  51. Example.Grid = Ext.extend(Ext.grid.GridPanel, {
  52.  
  53. // defaults - configurable from outside
  54. border:false
  55. ,autoScroll:true
  56. ,viewConfig:{forceFit:true}
  57. ,layout:'fit'
  58. ,enableDragDrop:true
  59. ,initComponent:function() {
  60.  
  61. // hard coded config - cannot be changed from outside
  62. var config = {
  63. columns:[
  64. {dataIndex:'firstName', header:'First Name'}
  65. ,{dataIndex:'midName', header:'First Name'}
  66. ,{dataIndex:'lastName', header:'First Name'}
  67. ,{dataIndex:'note', header:'Note'}
  68. ]
  69. };
  70.  
  71. // apply config
  72. Ext.apply(this, Ext.apply(this.initialConfig, config));
  73.  
  74. // call parent
  75. Example.Grid.superclass.initComponent.apply(this, arguments);
  76.  
  77. } // eo function initComponent
  78. ,onRender:function() {
  79. Example.Grid.superclass.onRender.apply(this, arguments);
  80.  
  81. this.dz = new Example.GridDropZone(this, {ddGroup:this.ddGroup || 'GridDD'});
  82. } // eo function onRender
  83.  
  84. /**
  85. * Called when records are dropped on this grid
  86. * @param {Ext.grid.GridPanel} srcGrid The source grid
  87. * @param {Array} records Array of dropped records
  88. */
  89. ,onRecordsDrop:Ext.emptyFn
  90.  
  91. }); // eo extend
  92.  
  93. // }}}
  94. // {{{
  95. Example.Grid1 = Ext.extend(Example.Grid, {
  96.  
  97. initComponent:function() {
  98.  
  99. // hard coded config - cannot be changed from outside
 100. var config = {
 101. store:new Ext.data.SimpleStore({
 102. id:0
 103. ,fields:['id', 'firstName', 'midName', 'lastName', 'note']
 104. ,data:[
 105. [1, 'Joe', 'John', 'Doe', 'Drag me!']
 106. ,[2, 'Bill', 'M.', 'Smith', 'Drag me!']
 107. ,[3, 'Mary', 'Lee', 'White', 'Drag me!']
 108. ,[4, 'Ann', 'A.', 'Berry', 'Drag me!']
 109. ,[5, 'Max', 'Larry', 'Lee', 'Drag me!']
 110. ,[6, 'Harry','Frank', 'Louis', 'Drag me!']
 111. ]
 112. })
 113. };
 114.  
 115. // apply config
 116. Ext.apply(this, Ext.apply(this.initialConfig, config));
 117.  
 118. // call parent
 119. Example.Grid1.superclass.initComponent.apply(this, arguments);
 120.  
 121. } // eo function initComponent
 122. }); // eo extend
 123.  
 124. // register xtype
 125. Ext.reg('examplegrid1', Example.Grid1);
 126. // }}}
 127. // {{{
 128. Example.Grid2 = Ext.extend(Example.Grid, {
 129.  
 130. initComponent:function() {
 131.  
 132. // hard coded config - cannot be changed from outside
 133. var config = {
 134. store:new Ext.data.SimpleStore({
 135. id:0
 136. ,fields:['id', 'firstName', 'midName', 'lastName', 'note']
 137. ,data:[
 138. [7, 'Carlos', '', 'Mitchel', 'Drag me!']
 139. ,[8, 'Ron', 'W.', 'Brown', 'Drag me!']
 140. ,[9, 'Alex', 'G.', 'Lem', 'Drag me!']
 141. ,[10, 'Frank', '', 'Amber', 'Drag me!']
 142. ,[11, 'Ashley', 'Edward', 'Anderson', 'Drag me!']
 143. ,[12, 'Bernice','C.', 'Dexter', 'Drag me!']
 144. ]
 145. })
 146. };
 147.  
 148. // apply config
 149. Ext.apply(this, Ext.apply(this.initialConfig, config));
 150.  
 151. // call parent
 152. Example.Grid2.superclass.initComponent.apply(this, arguments);
 153.  
 154. } // eo function initComponent
 155. }); // eo extend
 156.  
 157. // register xtype
 158. Ext.reg('examplegrid2', Example.Grid2);
 159. // }}}
 160.  
 161. Ext.BLANK_IMAGE_URL = './ext/resources/images/default/s.gif';
 162.  
 163. // application main entry point
 164. Ext.onReady(function() {
 165.  
 166. Ext.QuickTips.init();
 167.  
 168. // create and show window
 169. var win = new Ext.Window({
 170. layout:'border'
 171. ,width:680
 172. ,height:240
 173. ,title:Ext.getDom('page-title').innerHTML
 174. ,items:[{
 175. xtype:'examplegrid1'
 176. ,region:'west'
 177. ,width:340
 178. ,split:true
 179. },{
 180. xtype:'examplegrid2'
 181. ,region:'center'
 182. }]
 183. });
 184. win.show();
 185.  
 186. }); // eo function onReady
 187.  
 188. // eof
