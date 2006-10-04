I have produced a new module for a site I am working on and would like to share it with the Drupal community.  While not quit ready for release it is usable and I would appreciate feedback on how it fits into Drupal, what is extra and what might be added.

An example of this in use can be found at http://test.wnpj.org (This is a test site)
zip file attached with module, mysql file, default css file, support.inc and installation instructions.

The module allows for breaking a page into sections similiar to the sections module but with a finer control on content.
With it you can define one or more layout pages, each layout page is then referenced by the name layout/pagename (pagename is something you assign).  For example, on the test site the layout page is called frontpage and
under administer -> settings I have set 

Each layout page is broken into sections.  A layout page can be layed out as either weighted rows (each section is one row in the output, order is determined by weight) or in a grid, placing each section in a row/column.  In grid mode a section can span rows/columns.  A section can have an optional "tab" which is used to label the section on output.

Each section can be broken into layout blocks which are layed out in a row within the section.  Each layout block can contain a 	1) Drupal block (add block)
	2) a Drupal node (add node)
	3) a mixes list of Drupal nodes (add node)
	4) a list of Drupal nodes of a given type. (add list)
	
The difference between a "node" and a "list" is the degree of control you have.  When adding a node you can specify a single node or list of nodes but the content is only changed by editing the list (or the node).
With a list you can specify a table to join with (say event), specify the sort order and add a customer where clause.  So with a list you can make a layout block that contains the list of events for the current week.

So before looking at each of the parts in more detail, lets look at the common features.

A layout page, section and block can be viewed by any one, only people not logged in, only people logged in or people with a specified permission.  For a page, if it can not be views the user sees the "Access Denied" page.  For layout sections and blocks no output is produced.

For layout sections and blocks you can specify a ccs class that can be used to control the look of the section or block.  So for example on test.wnpj.org, Alerts have a css class specified for the section to allow for a different color scheme than the rest.

Details for layout pages, sections and blocks.  Items with a * have a common footnote below on formatting control.

Layout page
	Has a page name, used to access/display the page with the path layout/pagename
	Can specify a css file to include to control appearance of the page.
	Layout choice (weighted rows, grid)
	Permission controls
	
Layout section
	*An optional "tab" header.  Appearance controlled through css
	A description (defaults to tab header).  Used in adminstative interface
	An enable flag (only enabled sections show in the output)
	Optional width  (can be used to control the width of the section, also can be done is css)
	If weight row layout, a weight for the section.
	If grid layout, a row, column, row span and column span.
		(Note, if you change page layout type, existing data is retained so if you switch back the old values are used)
	Optional css class
	Permission controls
	Optional section header (if content).  If specified and the section has content, placed above the layout blocks.
	Optional section header (if no content).  If specified and the section does not have content, the specified html is used
			(Note: Can be used to make a section with fixed content like a welcome message without making a node)
	Optional section footer. If specified and the section has content, placed below the layout blocks.
	Hide if no content flag, if set and section has no content, "if not content header" is ignored and section produces no output.
	
Layout block, common
	Description - Used in admin interface to identify block
	Weight - Determines order in section.
	Enable flag (only enabled blocks show in output)
	Optional width
	css class
	
Layout block, type: Drupal block
	Source block - drop down list of available blocks
	Display control - Content of block only or title and content.
	
Layout block, type: Node (or list of nodes)
	Node path list - a list of comma seperated nodes or path aliases (ex: 1,2,TaskForce,node/4)
	Display control
		Title
		Content Only
		Title and content
		Full Node (display by node type)
		Full Node with teaser (teaser instead of full body)
	*Title source, used if 'title' or 'title and content' picked for display, default @title@
	*Content source, used if 'content' or 'title and content' picked for display, default @body@
	Limit Content, used if 'content' or 'title and content' picked for display, default 0 (don't limit)
			If set, only used specified number of characters from content.
			
Layout block, type: List
	Source module - used to select type of node that will be displayed
	Display control
		Title
		Content Only
		Title and content
		Full Node (display by node type)
		Full Node with teaser (teaser instead of full body)
	*Title source, used if 'title' or 'title and content' picked for display, default @title@
	*Content source, used if 'content' or 'title and content' picked for display, default @body@
	Limit Content, used if 'content' or 'title and content' picked for display, default 0 (don't limit)
			If set, only used specified number of characters from content.
	'Sort by Sticky' flag, if set sticky items show at top of list.
	Ablity to select on node status, promote (frontpage) and sticky (each can be don't care, on, or off)
	WARNING - the following options require some internal knowledge of the selected source module
	Optional join table, example for the event module this would be event
			Allows limiting and sorting based on fields in the join table
	*Optional custom sort: example for event module: "start"
			Note: if 'sort by sticky' is set this appended to that.
	Optional where clause: see 'formatting' header for example
	
Formatting tab header text, title, content and where clause
	The basic forms are
		text - just plain text, shows as entered
		@field_name@ - field in node table and for list in join table (does not apply to tab header)
				Ex: @title@ uses the title field of node
		{php code} - Output of php code is used

	These can be combined, here are some examples from test.wnpj.org
	
		event tab: Events for week of {layout_this_week_range(); }
			See support.inc for layout_this_week_range()
		event listing title: {event_format_date_range($node);}<br>{event_city($node, true);} - @title@
			event_format_date_range() and event_city() are custom functions.
			Note: There is a variable $node is scope at the time the function is called
						This contains the current node ( and for list the joined table )
		event list where clause: {layout_sql_this_week();}
			See support.inc for layout_sql_this_week()
						
	Special note: By the time formatting happens title has been modified to be inclosed in a link to the actual node.
	
Support.inc (preliminary)
	Contains functions that can be used in formatting
	
	layout_this_week($dow=1, $week_offset=0, $format = "")
		dow - dow of week to use for date, defaults to monday
		week_offset - can get date for week relative to this week
		format - valid format to date function
				if set, layout_this_week returns formatted date string
				else layout_this_week returns a date
				
	layout_this_week_range($week_offset=0, $format = "")
		Returns a string in format "Start date - End Date"
		week_offset - can get date for week relative to this week
		format - If sepecified, start date and end date are formatted as specifed
			Otherwise if start and end date are in same year 'M j' is used the format
			Otherwise if in different years 'M j Y' is used for the format
			
	layout_sql_this_week($clip=false)
		clip - not yet implemented, purpose is to limit dates for today to end of week
		For use with event module, returns sql where clause that selected entries only for current week.
		
	layout_mixed_type($node) - EXAMPLE
		Shows how one could format the title based on node type.
		
Output appearance
	Fair degree of control in ouput controlled by css and theme functions
	(Current version is preliminary and subject to change)
	
Using:
	After installing the module and adding the tables to the database
	Enable the module under administer -> module
	This will add a new entry under administer called layout
	From layout you add layout pages, sections and blocks
	Once you add a page you can add sections.
	Once you add a section you can add blocks
	
