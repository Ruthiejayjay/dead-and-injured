const Ziggy = {"url":"http:\/\/localhost","port":null,"defaults":{},"routes":{"duel.index":{"uri":"duel","methods":["GET","HEAD"]},"duel.create":{"uri":"duel\/create","methods":["POST"]},"duel.join":{"uri":"duel\/join","methods":["POST"]},"duel.room":{"uri":"duel\/room\/{code}","methods":["GET","HEAD"],"parameters":["code"]},"duel.set-code":{"uri":"duel\/room\/{code}\/set-code","methods":["POST"],"parameters":["code"]},"duel.guess":{"uri":"duel\/room\/{code}\/guess","methods":["POST"],"parameters":["code"]},"storage.local":{"uri":"storage\/{path}","methods":["GET","HEAD"],"wheres":{"path":".*"},"parameters":["path"]},"storage.local.upload":{"uri":"storage\/{path}","methods":["PUT"],"wheres":{"path":".*"},"parameters":["path"]}}};
if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
  Object.assign(Ziggy.routes, window.Ziggy.routes);
}
export { Ziggy };
