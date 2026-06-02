var e=`
[data-edit-field] {
  cursor: pointer;
}
[data-edit-field]:hover {
  outline: 2px dashed #3b82f6;
  outline-offset: 4px;
  border-radius: 2px;
}
.ple-chip {
  position: fixed;
  bottom: 72px;
  right: 16px;
  z-index: 99998;
  display: flex;
  align-items: center;
  gap: 6px;
  background: #3b82f6;
  color: #fff;
  font: 600 10px/1 system-ui, sans-serif;
  letter-spacing: .08em;
  text-transform: uppercase;
  padding: 7px 11px;
  border-radius: 100px;
  box-shadow: 0 2px 12px rgba(0,0,0,.25);
  pointer-events: none;
  user-select: none;
}
.ple-backdrop {
  display: none;
  position: fixed;
  inset: 0;
  z-index: 99999;
  background: rgba(0,0,0,.55);
  backdrop-filter: blur(3px);
  -webkit-backdrop-filter: blur(3px);
  align-items: center;
  justify-content: center;
}
.ple-backdrop.open {
  display: flex;
}
.ple-modal {
  background: #fff;
  color: #111;
  border-radius: 14px;
  padding: 28px;
  width: min(620px, calc(100vw - 32px));
  box-shadow: 0 24px 64px rgba(0,0,0,.4);
  display: flex;
  flex-direction: column;
  gap: 14px;
}
.ple-modal-label {
  font: 600 10px/1 system-ui, sans-serif;
  letter-spacing: .1em;
  text-transform: uppercase;
  color: #6b7280;
}
.ple-input, .ple-textarea {
  width: 100%;
  border: 1.5px solid #d1d5db;
  border-radius: 8px;
  padding: 10px 12px;
  font: 400 14px/1.6 system-ui, sans-serif;
  color: #111;
  outline: none;
  box-sizing: border-box;
  resize: vertical;
  transition: border-color .15s, box-shadow .15s;
}
.ple-input:focus, .ple-textarea:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59,130,246,.15);
}
.ple-hint {
  font: 400 11px/1 system-ui, sans-serif;
  color: #9ca3af;
  margin-top: -6px;
}
.ple-rte-wrap {
  border: 1.5px solid #d1d5db;
  border-radius: 8px;
  overflow: hidden;
  transition: border-color .15s, box-shadow .15s;
}
.ple-rte-wrap:focus-within {
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59,130,246,.15);
}
.ple-rte-toolbar {
  display: flex;
  gap: 2px;
  padding: 6px 8px;
  background: #f9fafb;
  border-bottom: 1px solid #e5e7eb;
}
.ple-rte-toolbar button {
  border: none;
  background: transparent;
  border-radius: 5px;
  padding: 4px 8px;
  font: 600 13px/1 system-ui, sans-serif;
  color: #374151;
  cursor: pointer;
  transition: background .12s;
  min-width: 28px;
}
.ple-rte-toolbar button:hover { background: #e5e7eb; }
.ple-rte-toolbar button.active { background: #dbeafe; color: #1d4ed8; }
.ple-rte-toolbar .sep {
  width: 1px;
  background: #e5e7eb;
  margin: 3px 4px;
}
.ple-rte {
  min-height: 140px;
  max-height: 320px;
  overflow-y: auto;
  padding: 10px 12px;
  font: 400 14px/1.6 system-ui, sans-serif;
  color: #111;
  outline: none;
  box-sizing: border-box;
}
.ple-rte em, .ple-rte i { font-style: italic; }
.ple-rte strong, .ple-rte b { font-weight: 700; }
.ple-rte a { color: #2563eb; text-decoration: underline; }
.ple-actions {
  display: flex;
  align-items: center;
  gap: 8px;
  justify-content: flex-end;
  margin-top: 4px;
}
.ple-status {
  font: 400 12px/1 system-ui, sans-serif;
  color: #9ca3af;
  margin-right: auto;
  min-height: 14px;
}
.ple-status.ok    { color: #10b981; }
.ple-status.error { color: #ef4444; }
.ple-btn {
  border: none;
  border-radius: 8px;
  padding: 9px 18px;
  font: 600 13px/1 system-ui, sans-serif;
  cursor: pointer;
  transition: opacity .15s, background .15s;
  white-space: nowrap;
}
.ple-btn:disabled { opacity: .45; cursor: not-allowed; }
.ple-btn-cancel { background: #f3f4f6; color: #374151; }
.ple-btn-cancel:hover:not(:disabled) { background: #e5e7eb; }
.ple-btn-save { background: #3b82f6; color: #fff; }
.ple-btn-save:hover:not(:disabled) { background: #2563eb; }
[data-edit-admin] {
  cursor: pointer;
  position: relative;
}
[data-edit-admin]:hover {
  outline: 2px dashed #f59e0b;
  outline-offset: 4px;
  border-radius: 2px;
}
[data-edit-admin]::after {
  content: 'Manage in WP Admin →';
  position: absolute;
  top: 0;
  right: 0;
  background: #f59e0b;
  color: #fff;
  font: 600 9px/1 system-ui, sans-serif;
  letter-spacing: .08em;
  text-transform: uppercase;
  padding: 4px 9px;
  border-radius: 4px;
  pointer-events: none;
  opacity: 0;
  transition: opacity .15s;
  white-space: nowrap;
  z-index: 10;
}
[data-edit-admin]:hover::after {
  opacity: 1;
}
`,t=null;function n(){if(!document.getElementById(`wpadminbar`))return;let n=document.createElement(`style`);n.textContent=e,document.head.appendChild(n);let r=document.createElement(`div`);r.className=`ple-chip`,r.setAttribute(`aria-hidden`,`true`),r.innerHTML=`
    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
    </svg>
    Edit Mode`,document.body.appendChild(r);let i=document.createElement(`div`);i.className=`ple-backdrop`,i.setAttribute(`role`,`presentation`),i.innerHTML=`
    <div class="ple-modal" role="dialog" aria-modal="true" aria-labelledby="ple-label">
      <div id="ple-label" class="ple-modal-label"></div>
      <div id="ple-field-wrap"></div>
      <p class="ple-hint" id="ple-hint" hidden>HTML markup is supported</p>
      <div class="ple-actions">
        <span class="ple-status" id="ple-status"></span>
        <button class="ple-btn ple-btn-cancel" id="ple-cancel" type="button">Cancel</button>
        <button class="ple-btn ple-btn-save" id="ple-save" type="button">Save</button>
      </div>
    </div>`,document.body.appendChild(i);let a=document.getElementById(`ple-label`),o=document.getElementById(`ple-field-wrap`),s=document.getElementById(`ple-hint`),c=document.getElementById(`ple-status`),l=document.getElementById(`ple-save`),u=document.getElementById(`ple-cancel`);function d(){i.classList.remove(`open`),t=null,c.textContent=``,c.className=`ple-status`,o.innerHTML=``}function f(e){t=e;let n=e.dataset.editType||`text`,r=e.dataset.editLabel||e.dataset.editField||`Edit field`,l=n===`wysiwyg`,u=l?e.innerHTML.trim():n===`nl2br`?(e.innerText??e.textContent).trim():e.textContent.trim();if(a.textContent=r,s.hidden=!0,o.innerHTML=``,c.textContent=``,c.className=`ple-status`,l){let e=document.createElement(`div`);e.className=`ple-rte-wrap`;let t=document.createElement(`div`);t.className=`ple-rte-toolbar`;function n(e,t,n,r){let i=document.createElement(`button`);return i.type=`button`,i.textContent=e,i.title=t,i.addEventListener(`mousedown`,e=>{if(e.preventDefault(),n===`createLink`){let e=prompt(`Enter URL:`);e&&document.execCommand(`createLink`,!1,e)}else n===`removeLink`?document.execCommand(`unlink`,!1,null):document.execCommand(n,!1,r??null)}),i}t.appendChild(n(`B`,`Bold`,`bold`)),t.appendChild(n(`I`,`Italic`,`italic`)),t.appendChild(n(`U`,`Underline`,`underline`));let r=document.createElement(`div`);r.className=`sep`;let i=document.createElement(`div`);i.id=`ple-field`,i.className=`ple-rte`,i.contentEditable=`true`,i.innerHTML=u,e.appendChild(t),e.appendChild(i),o.appendChild(e)}else if(n===`textarea`||n===`nl2br`){let e=document.createElement(`textarea`);e.id=`ple-field`,e.className=`ple-textarea`,e.rows=6,e.value=u,o.appendChild(e)}else{let e=document.createElement(`input`);e.id=`ple-field`,e.className=`ple-input`,e.type=n===`url`?`url`:`text`,e.value=u,o.appendChild(e)}i.classList.add(`open`),requestAnimationFrame(()=>document.getElementById(`ple-field`)?.focus())}async function p(){if(!t)return;let e=document.getElementById(`ple-field`),n=t.dataset.editField,r=t.dataset.editType||`text`,i=parseInt(t.dataset.editPost||window.planetarioEditor?.postId||`0`,10),a=e.contentEditable===`true`?e.innerHTML:e.value;if(!i||!n){c.textContent=`Missing field or post ID.`,c.className=`ple-status error`;return}l.disabled=!0,u.disabled=!0,c.textContent=`Saving…`,c.className=`ple-status`;try{let e=await(await fetch(window.planetarioEditor.apiUrl,{method:`POST`,credentials:`same-origin`,headers:{"Content-Type":`application/json`,"X-WP-Nonce":window.planetarioEditor.nonce},body:JSON.stringify({post_id:i,field_name:n,field_type:r,value:a})})).json();if(e.success)r===`wysiwyg`?t.innerHTML=e.value??a:t.textContent=e.value??a,c.textContent=`Saved!`,c.className=`ple-status ok`,setTimeout(d,800);else throw Error(e.message||`Save failed.`)}catch(e){c.textContent=e.message||`An error occurred.`,c.className=`ple-status error`}finally{l.disabled=!1,u.disabled=!1}}l.addEventListener(`click`,p),u.addEventListener(`click`,d),i.addEventListener(`click`,e=>{e.target===i&&d()}),document.addEventListener(`keydown`,e=>{e.key===`Escape`&&i.classList.contains(`open`)&&d()}),document.querySelectorAll(`[data-edit-field]`).forEach(e=>{e.addEventListener(`click`,t=>{t.stopPropagation(),f(e)})}),document.querySelectorAll(`[data-edit-admin]`).forEach(e=>{e.addEventListener(`click`,t=>{t.stopPropagation();let n=e.dataset.editAdmin,r=window.planetarioEditor?.adminUrl||`/wp-admin/`;window.open(r+n,`_blank`)})})}document.addEventListener(`DOMContentLoaded`,n);