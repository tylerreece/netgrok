-- 'luci-cbi-netgrok' is the config file located in /etc/config/
map = Map("luci-cbi-netgrok", translate("Title"), translate("Description"))

-- 'info' is the section called "info' in the luci-cbi-netgrok file
map_section = map:section(TypedSection, "info", "Part A of the form")

-- 'name' is the option in the luci-cbi-netgrok file
map_section_option = map_section:option(Value, "name", "Name");
map_section_option.optional = false;
map_section_option.rmempty = false;

return map
