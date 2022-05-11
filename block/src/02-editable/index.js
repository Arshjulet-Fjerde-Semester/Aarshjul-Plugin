const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

registerBlockType("podkit/editable", {
  title: __("Aarshjul", "podkit"),
  category: "podkit",

  edit() {
    return (
      <div>
          <iframe id='webgl_iframe' frameborder="0" allow="autoplay; fullscreen; vr" allowfullscreen="" allowvr=""
        mozallowfullscreen="true" src="./../wp-content/plugins/aarshjul-plugin/block/src/02-editable/index.html"  width="810"
        height="640" onmousewheel="" webkitallowfullscreen="true"></iframe>
      <div class="modal fade bd-example-modal-lg " id="pupOutWindow" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg h100">
        <div class="modal-content h100">
            <iframe src="" id="pdfPopUp" frameborder="0" class="h100"></iframe>
        </div>
      </div>
    </div>

      </div>
    //<div>
    //  <iframe data-v-bfbef82a="" allow="geolocation; microphone; camera; midi; encrypted-media;" src="https://i.simmer.io/@stiggyman/test" allowfullscreen="allowfullscreen" class="responsive-frame"></iframe>
    //</div>
    );
  },
  save() {
    return (
      <div>
        <iframe id='webgl_iframe' frameborder="0" allow="autoplay; fullscreen; vr" allowfullscreen="" allowvr=""
      mozallowfullscreen="true" src="./../wp-content/plugins/aarshjul-plugin/block/src/02-editable/index.html"  width="810"
      height="640" onmousewheel="" webkitallowfullscreen="true"></iframe>
      <div class="modal fade bd-example-modal-lg " id="pupOutWindow" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg h100">
        <div class="modal-content h100">
            <iframe src="" id="pdfPopUp" frameborder="0" class="h100"></iframe>
        </div>
      </div>
    </div>

      </div>
  //  <div>
  //  <iframe data-v-bfbef82a="" allow="geolocation; microphone; camera; midi; encrypted-media;" src="https://i.simmer.io/@stiggyman/test" allowfullscreen="allowfullscreen" class="responsive-frame"></iframe>
  //</div>
    );
  }
});
