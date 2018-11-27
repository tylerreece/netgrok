-- notice that "netgrok" is the name of the file netgrok.lua
module("luci.controller.netgrok.netgrok", package.seeall)

function index()
	-- this adds the top level tab and defaults to the first sub-tab (netgrok)
	-- it is set to position 60
	entry({"admin", "netgrok"}, firstchild(), "NetGrok", 60).dependent = false

	-- this adds the first sub-tab, located in
	-- <luci-path>/luci-app-netgrok/model/cbi/netgrok-module
	-- the file is called netgrok-tab-1.lua, and is set to the first position
	entry({"admin", "netgrok", "netgrok"}, cbi("netgrok-module/netgrok-tab-1"), "NetGrok", 1)

	-- this adds the second sub-tab, located in
	-- <luci-path>/luci-app-netgrok/view/netgrok-module
	-- the file is called netgrok-tab-2.htm, and is set to the second position
	entry({"admin", "netgrok", "netgrok-tab-2"}, template("netgrok-module/netgrok-tab-2"), "NetGrok", 2)
end
