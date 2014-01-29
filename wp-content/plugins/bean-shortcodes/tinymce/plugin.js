// closure to avoid namespace collision
(function () {
	// create the plugin
	tinymce.create("tinymce.plugins.BeanShortcodes", {
	
		init: function ( ed, url ) {
			ed.addCommand("beanPopup", function ( a, params ) {
				var popup = params.identifier;
				
				// load thickbox
				tb_show("Insert Bean Shortcode", url + "/popup.php?popup=" + popup + "&width=" + 800);
			});
		},
		
		// creates control instances based on the control's id.
		// our button's id is "bean_button"
		
		createControl: function ( btn, e ) {
			if ( btn == "bean_button" ) {	
				
				var a = this;
				
				// creates the button
				var btn = e.createSplitButton('bean_button', {
                    title: "Insert Bean Shortcode", // title of the button
					image: BeanShortcodes.plugin_folder +"/tinymce/images/icon.png", // path to the button's image
					icons: false
                });
				
				//Render a DropDown Menu
                btn.onRenderMenu.add(function (c, b) {	
                				
					a.addWithPopup( b, "Alerts", "alert" );
					a.addWithPopup( b, "Buttons", "button" );			
					a.addWithPopup( b, "Columns", "columns");
					a.addImmediate( b, "Highlight", "<br>[highlight]Place your highlighted text right here.[/highlight]<br>" );
					a.addWithPopup( b, "Feature Areas", "featurearea");
					a.addImmediate( b, "Lists", "[list]<li></li>[/list]" );
					a.addImmediate( b, "Note", "<br>[note]Place your note text right here.[/note]<br>" );
					a.addWithPopup( b, "Popup", "popup" );
					a.addImmediate( b, "Pre", "[pre]Place your code right here.[/pre]" );
					a.addImmediate( b, "Pull Quote", "[quote]Place your pull quote text right here.[/quote]" );
 					a.addWithPopup( b, "Social Icons", "social-icons" );
					a.addWithPopup( b, "Tabs","tabs");
					a.addWithPopup( b, "Toggles","toggle");
					   
				});
                
                return btn;
			}
			
			return null;
		},
		
		addWithPopup: function ( ed, title, id ) {
			ed.add({
				title: title,
				onclick: function () {
					tinyMCE.activeEditor.execCommand("beanPopup", false, {
						title: title,
						identifier: id
					})
				}
			})
		},

		//Insert shortcode into content
		addImmediate: function ( ed, title, sc) {
			ed.add({
				title: title,
				onclick: function () {
					tinyMCE.activeEditor.execCommand( "mceInsertContent", false, sc )
				}
			})
		},
		
		// creds
		getInfo: function () {
			return {
				longname : "ThemeBeans Shortcodes",
				author : 'ThemeBeans',
				authorurl : 'http://themebeans.com/',
				infourl : 'http://themebeans.com/plugin/bean-shortcodes-plugin',
				version : "1.0"
			};
		}
	});
	
	// add BeanShortcodes plugin
	tinymce.PluginManager.add("BeanShortcodes", tinymce.plugins.BeanShortcodes);
})();