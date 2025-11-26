import docker
import json

def check_containers():
    try:
        client = docker.from_env()
        containers = client.containers.list(all=True)
        results = []

        for container in containers:
            info = {
                "name": container.name,
                "image": container.image.tags,
                "running_as_root": container.attrs["Config"]["User"] in ["", "root"],
                "privileged": container.attrs["HostConfig"]["Privileged"],
                "ports": container.attrs["NetworkSettings"]["Ports"],
                "mounts": container.attrs["Mounts"],
            }
            results.append(info)

        return {"docker": results}

    except Exception as e:
        return {"docker": f"ERROR: {str(e)}"}


if __name__ == "__main__":
    print(json.dumps(check_containers()))
