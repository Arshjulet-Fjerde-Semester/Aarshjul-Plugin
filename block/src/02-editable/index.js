const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

registerBlockType("podkit/editable", {
  title: __("Aarshjul", "podkit"),
  category: "podkit",

  edit() {
    return (
    //   <iframe id='webgl_iframe' frameborder="0" allow="autoplay; fullscreen; vr" allowfullscreen="" allowvr=""
    // mozallowfullscreen="true" src="./../wp-content/plugins/aarshjul-plugin/block/src/02-editable/index.html"  width="810"
    // height="640" onmousewheel="" webkitallowfullscreen="true"></iframe>
    <div>
      <iframe data-v-bfbef82a="" allow="geolocation; microphone; camera; midi; encrypted-media;" src="https://i.simmer.io/@stiggyman/test" allowfullscreen="allowfullscreen" class="responsive-frame"></iframe>
    </div>
    );
  },
  save() {
    return (
    //   <iframe id='webgl_iframe' frameborder="0" allow="autoplay; fullscreen; vr" allowfullscreen="" allowvr=""
    // mozallowfullscreen="true" src="./../wp-content/plugins/aarshjul-plugin/block/src/02-editable/index.html"  width="810"
    // height="640" onmousewheel="" webkitallowfullscreen="true"></iframe>
    <div>
    <iframe data-v-bfbef82a="" allow="geolocation; microphone; camera; midi; encrypted-media;" src="https://i.simmer.io/@stiggyman/test" allowfullscreen="allowfullscreen" class="responsive-frame"></iframe>
  </div>
    );
  }
});
