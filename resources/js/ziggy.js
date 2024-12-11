const Ziggy = {"url":"http:\/\/colibri.my","port":null,"defaults":{},"routes":{"videos":{"uri":"\/","methods":["GET","HEAD"]},"articles":{"uri":"articles","methods":["GET","HEAD"]},"storage.local":{"uri":"storage\/{path}","methods":["GET","HEAD"],"wheres":{"path":".*"},"parameters":["path"]}}};
if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
  Object.assign(Ziggy.routes, window.Ziggy.routes);
}
export { Ziggy };
