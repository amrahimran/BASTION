import json
import uuid
from smbprotocol.connection import Connection, Dialects
from smbprotocol.session import Session
from smbprotocol.tree import TreeConnect
from smbprotocol.open import Open
from smbprotocol.exceptions import SMBException
import sys

def scan_smb(target):
    shares = ["IPC$", "C$", "ADMIN$", "Users", "Public", "Shared", "Share", "Data"]
    result = {"shares": []}

    try:
        client_guid = uuid.uuid4()
        conn = Connection(client_guid, target, 445)
        conn.connect(Dialects.SMB_3_0_2)

        session = Session(conn, username="", password="")
        session.connect()

        for share in shares:
            info = {"name": share, "accessible": False, "read_access": False}
            try:
                tree = TreeConnect(session, fr"\\{target}\{share}")
                tree.connect()
                info["accessible"] = True

                try:
                    handle = Open(tree, "")  # just open the root
                    # Try to query directory, but do not assume API signatures
                    try:
                        entries = handle.query_directory("*")
                        if entries:
                            info["read_access"] = True
                    except Exception:
                        # maybe query_directory not supported in this version
                        info["read_access"] = True
                    handle.close()
                except SMBException:
                    pass

                tree.disconnect()
            except SMBException:
                pass

            result["shares"].append(info)

        session.disconnect()
        conn.disconnect()
    except Exception as e:
        result["error"] = str(e)

    return result

if __name__ == "__main__":
    target_ip = sys.argv[1] if len(sys.argv) > 1 else "127.0.0.1"
    res = scan_smb(target_ip)
    print(json.dumps(res))
