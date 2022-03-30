const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

registerBlockType("podkit/editable", {
  title: __("Aarshjul", "podkit"),
  category: "podkit",

  edit() {
    return (
      <iframe id='webgl_iframe' frameborder="0" allow="autoplay; fullscreen; vr" allowfullscreen="" allowvr=""
    mozallowfullscreen="true" src="./../wp-content/plugins/podkit/src/02-editable/index.html"  width="810"
    height="640" onmousewheel="" webkitallowfullscreen="true"></iframe>
    );
  },
  save() {
    return (
      <iframe id='webgl_iframe' frameborder="0" allow="autoplay; fullscreen; vr" allowfullscreen="" allowvr=""
    mozallowfullscreen="true" src="./../wp-content/plugins/podkit/src/02-editable/index.html"  width="810"
    height="640" onmousewheel="" webkitallowfullscreen="true"></iframe>
    );
  }
});
